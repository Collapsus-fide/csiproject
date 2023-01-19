from collections import Counter
import numpy as np
import pandas as pd
from sklearn.model_selection import train_test_split
import pickle
import sys

class ID3DecisionTreeClassifier:
    def __init__(self, min_samples_split=2, max_depth=100):
        self.min_samples_split = min_samples_split
        self.max_depth = max_depth

    def fit(self, X, y, fileName):
        self.n_features = X.shape[1]       #nombre de colonnes de la matrice X
        self.n_classes = len(np.unique(y)) #nombre de classes différentes dans le vecteur y
        self.tree = self._grow_tree(X, y)  #construction de l'arbre de décision en utilisant l'algorithme ID3
        file = open(fileName, 'wb')        #Fichier de sauvegarde
        pickle.dump(self.tree, file)       #Sauvegarde de l'arbre dans le fichier
        file.close()                       #Fermeture du fichier

    def load(self, X, y, fileName):
        self.n_features = X.shape[1]       #nombre de colonnes de la matrice X
        self.n_classes = len(np.unique(y)) #nombre de classes différentes dans le vecteur y
        file = open(fileName, 'rb')        #Ouverture du fichier de sauvegarde
        self.tree = pickle.load(file)      #chargement de l'arbre de décision
        file.close()                       #Fermeture du fichier

    def _grow_tree(self, X, y, depth=0):
        n_samples, n_features = X.shape
        n_classes = len(np.unique(y))

        # Arrêt de la récursion si l'on atteint la profondeur maximale ou si le nombre de samples est inférieur au nombre minimal requis
        if depth >= self.max_depth or n_samples < self.min_samples_split:
            leaf_value = self._most_common_class(y)
            return LeafNode(leaf_value)

        # Calcul de l'entropie de Shannon de y
        shannon_entropy = self._calculate_shannon_entropy(y)

        # Initialisation des variables pour trouver la meilleure division
        best_gain = 0
        best_feature = None
        best_threshold = None

        # Boucle sur toutes les features
        for feature_idx in range(n_features):
            # Récupération de toutes les valeurs unique de la feature courante
            feature_values = np.unique(X[:, feature_idx])
            # Boucle sur toutes les valeurs unique de la feature courante
            for feature_value in feature_values:
                # Divisions des samples en deux groupes en fonction de la valeur de la feature courante
                X_left, y_left, X_right, y_right = self._split_on_feature(X, y, feature_idx, feature_value)
                # Si l'un des deux groupes est vide, on passe à la feature suivante
                if len(X_left) == 0 or len(X_right) == 0:
                    continue

                # Calcul du gain d'information
                gain = shannon_entropy - self._calculate_information_gain(y, y_left, y_right)

                # Si le gain d'information est supérieur au meilleur gain enregistré jusqu'à présent, on met à jour les variables
                if gain > best_gain:
                    best_gain = gain
                    best_feature = feature_idx
                    best_threshold = feature_value

        # Si aucun gain d'information n'a été trouvé, on crée une feuille avec la classe la plus fréquente
        if best_gain == 0:
            leaf_value = self._most_common_class(y)
            return LeafNode(leaf_value)

        # Sinon, on divise les samples en utilisant la meilleure feature et la meilleure valeur seuil trouvées
        X_left, y_left, X_right, y_right = self._split_on_feature(X, y, best_feature, best_threshold)

        # On crée deux nouveaux noeuds en appelant récursivement la fonction _grow_tree sur les deux groupes de samples divisés
        left_child = self._grow_tree(X_left, y_left, depth+1)
        right_child = self._grow_tree(X_right, y_right, depth+1)

        # On retourne un noeud interne avec les deux noeuds enfants créés et la feature et la valeur seuil utilisées pour la division
        return InternalNode(best_feature, best_threshold, left_child, right_child)

    def _split_on_feature(self, X, y, feature_idx, feature_value):
        """Divise les samples en deux groupes en fonction de la valeur de la feature spécifiée"""
        left_idx = X[:, feature_idx] < feature_value    # indique pour chaque ligne de X, si la valeur est inférieur au feature
        right_idx = X[:, feature_idx] >= feature_value
        return X[left_idx], y[left_idx], X[right_idx], y[right_idx]

    def _calculate_information_gain(self, y, y_left, y_right):
        """Calcule le gain d'information en utilisant l'entropie de Shannon"""
        p_left = len(y_left) / len(y)
        p_right = len(y_right) / len(y)
        return self._calculate_shannon_entropy(y) - p_left * self._calculate_shannon_entropy(y_left) - p_right * self._calculate_shannon_entropy(y_right)

    def _calculate_shannon_entropy(self, y):
        """Calcule l'entropie de Shannon de y"""
        counts = Counter(y)
        probs = [count / len(y) for count in counts.values()]  #création d'une liste de valeurs (%) qui correspond aux fréquences de chaque élément du compteur
        return -sum(p * np.log2(p) for p in probs)             #entropie de Shannon

    def _most_common_class(self, y):
        """Retourne la classe la plus fréquente dans y"""
        counts = Counter(y)                     #compteur d'occurences
        most_common = counts.most_common(1)[0]  # Récupération du tuple (élément, fréquence) de l'élément le plus fréquent
        return most_common[0]                   # Récupération de l'élément le plus fréquent

    def predict(self, X):
        """Prédit la classe des samples X en parcourant l'arbre de décision"""
        # pour chaque element de X, on traverse l'arbre de décision pour prédire la classe de l'exemple
        return np.array([self._traverse_tree(x, self.tree) for x in X])

    def _traverse_tree(self, x, node):
        """Parcours récursif de l'arbre de décision en utilisant la valeur de la feature du noeud courant pour déterminer dans quel sous-arbre continuer"""
        if isinstance(node, LeafNode):               # Si le noeud courant est une feuille
            return node.value                        # Retour de la classe prédite
        if x[node.feature] < node.threshold:         # Si la valeur du feature est inférieure ou égale au seuil du noeud courant
            return self._traverse_tree(x, node.left) # Traversée de l'arbre de décision sur le sous-arbre gauche
        return self._traverse_tree(x, node.right)    # Traversée de l'arbre de décision sur le sous-arbre droit

    def score(model, X_test, y_test):
        nb_correct = abs(model.predict(X_test)-y_test).sum()  # Calcul du nombre de prédictions correctes
        nb_predictions = len(y_test)                          # Calcul du nombre total de prédictions
        return nb_correct / nb_predictions                    # Calcul de la précision du modèle*

    def printSelfTree(self):
        if (isinstance(self.tree, LeafNode)):
            print("Leaf,")
            print(" Value : " + str(self.tree.value))
            return
        print("Node,")
        print(" Feature : " + str(self.tree.feature))
        print(" Thershold : " + str(self.tree.threshold))
        printTree(self.tree.left)
        printTree(self.tree.right)
        return

def printTree(arbre):
    if (isinstance(arbre, LeafNode)):
        print("Leaf,")
        print(" Value : " + str(arbre.value))
        return
    print("Node,")
    print(" Feature : " + str(arbre.feature))
    print(" Thershold : " + str(arbre.threshold))
    printTree(arbre.left)
    printTree(arbre.right)
    return

# --- Feuille
class LeafNode:
    def __init__(self, value):
        self.value = value

# --- Noeud de l'arbre (n'est pas une feuille)
# feature_idx : l'index de la feature utilisée pour diviser les samples en deux groupes en utilisant la valeur seuil.
# threshold : la valeur seuil utilisée pour diviser les samples en deux groupes en utilisant la feature.
# left : un noeud de l'arbre de décision qui correspond aux samples du groupe de gauche (c'est-à-dire aux samples pour lesquels la feature a une valeur inférieure à la valeur seuil).
# right : un noeud de l'arbre de décision qui correspond aux samples du groupe de droite (c'est-à-dire aux samples pour lesquels la feature a une valeur supérieure ou égale à la v
class InternalNode:
    def __init__(self, feature, threshold, left, right):
        self.feature = feature
        self.threshold = threshold
        self.left = left
        self.right = right

def getVehicleFromData(brand, diesel, electric, petrol, other, hybrid, automatic, manual, otherTr, SemiAuto, year, price, mileage, tax, mpg, engineSize):    #Sauvegarder un véhicule
    match brand:
        case "brand_audi":
            return pd.DataFrame(np.array([[1,0,0,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_bmw":
            return pd.DataFrame(np.array([[0,1,0,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_ford":
            return pd.DataFrame(np.array([[0,0,1,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_merc":
            return pd.DataFrame(np.array([[0,0,0,1,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_toyota":
            return pd.DataFrame(np.array([[0,0,0,0,1,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_vauxhall":
            return pd.DataFrame(np.array([[0,0,0,0,0,1,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_vw":
            return pd.DataFrame(np.array([[0,0,0,0,0,0,1,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])

def saveVehicle(fileName, vehicule):    #Sauvegarder un véhicule (unused)
    file = open(fileName, 'wb')
    pickle.dump(vehicule, file)
    file.close()


print ('Number of arguments:', len(sys.argv), 'arguments.')
if (len(sys.argv) < 2):
    print ('Wrong amount of arguments, at least 2 needed')
    sys.exit()
elif (len(sys.argv) > 18):
    print ('Too much arguments')
    sys.exit()
else: 
    print ('Argument List:', str(sys.argv))

#RECUPERATION DES DONNEES
#data = pd.read_csv('all_e.csv', nrows=100)
data = pd.read_csv('all_e.csv')

#CORRELATION
with pd.option_context('display.max_rows', None, 'display.max_columns', None):
 print(data.corr()["price"])

#DECOUPAGE DES DONNEES
# Générer le set de training. Fixer random_state pour répliquer les resultats ultérieurement.
train = data.sample(frac=0.8, random_state=1)
test = data.loc[~data.index.isin(train.index)]

# Sauvegarde véhicules à tester (depuis le site)
if (len(sys.argv) >= 3): 
    if (len(sys.argv) == 18):
        test = getVehicleFromData(str(sys.argv[2]), str(sys.argv[3]), str(sys.argv[4]), str(sys.argv[5]), str(sys.argv[6]), str(sys.argv[7]), str(sys.argv[8]), str(sys.argv[9]), str(sys.argv[10]), str(sys.argv[11]), str(sys.argv[12]), str(sys.argv[13]), str(sys.argv[14]), str(sys.argv[15]), str(sys.argv[16]), str(sys.argv[17]))
        test = test.astype(float)
    else:
        print("Erreur, arguments manquants")
        sys.exit()

# Test Audi
#test = pd.DataFrame(np.array([[1,0,0,0,0,0,0,0,0,0,0,1,0,1,0,0,2018,18000,15000,40,55,1.5]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
#saveVehicle("audiTest.sav", test)

#test = data.loc[(data["model_A3"] == 1) & (data["Petrol"] == 1) & (data["Manual"] == 1) & (data["year"] == 2015) & (data["mileage"] == 15000) & (data["engineSize"] == 1.4)]

#if (len(sys.argv) < 3):                                #Si seulement deux arguments
#    test = data.loc[~data.index.isin(train.index)]     # Sélectionner tout ce qui n'est pas dans le set de training et le mettre dans le set de test.
#else:                                                  #Sinon
#    test = saveVehicleFromData()
#    vehiculeFile = open("vehicule.sav", 'rb')          #Ouverture du fichier du vehicule
#    test = pickle.load(vehiculeFile)                   #chargement des données
#    vehiculeFile.close()                               #Fermeture du fichier

print("Test1")
print(test)

#FILTRAGE DES COLONNES
labels_train = train ["price"]
labels_test = test ["price"]
train = train [["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","mileage","tax","mpg","engineSize"]]
test  = test  [["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","mileage","tax","mpg","engineSize"]]

labels_train = labels_train.values
print(f'Average Values : {sum(labels_train)/len(labels_train)}')
labels_test = labels_test.values
train = train.values
test = test.values

# Chargement des données d'apprentissage et de test
X_train = train
X_test  = test
y_train = labels_train
y_test  = labels_test

print("id3")
# Initialisation de l'arbre de décision ID3
id3 = ID3DecisionTreeClassifier(min_samples_split=2, max_depth=100)

if (str(sys.argv[1]) == "load") :
    print("load")
    # Chargemet de l'arbre des données d'apprentissage
    id3.load(X_train, y_train, 'dataTree.sav')
else :
    print("fit")
    # Entraînement de l'arbre sur les données d'apprentissage
    id3.fit(X_train, y_train, 'dataTree.sav')

#Affichage de l'arbre
#print(id3.printSelfTree())

print("evaluation")
# Evaluation de la performance de l'arbre sur les données de test
precision = id3.score(X_test, y_test)
print(f'precision : {precision:.2f}')

# Prédiction de la classe de nouvelles observations
predictions = id3.predict(X_test)
print(f'Prediction : {predictions}')
print(f'Average Prediction : {sum(predictions)/len(predictions)}')
