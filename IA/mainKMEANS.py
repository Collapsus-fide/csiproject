import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
import pickle
import sys

def sauvegarde(fileName, object):
    file = open(fileName, 'wb')        #Fichier de sauvegarde
    pickle.dump(object, file)       #Sauvegarde de l'arbre dans le fichier
    file.close()                       #Fermeture du fichier

def normalisation(data):
    for column in data.columns:
        max = data[column].max()
        min = data[column].min()
        i = 0
        for row in data[column]:
            resultat = (row - min) / (max - min)
            data.loc[data.index[i], column] = resultat
            i = i + 1
    print(data)
    export_csv = data.to_csv(os.getcwd() + '/Truenormalisation.csv', index=None, header=True, encoding='utf-8', sep=',')

def denormalisation(data, centers):
    centersdf = pd.DataFrame(centers, columns=data.columns)
    print(centersdf)
    #pour chaque colonne
    for column in data.columns:
        max = data[column].max()
        min = data[column].min()
        i = 0
        for row in centersdf[column]:
            resultat = row * (max - min) + min
            centersdf[column][centersdf.index[i]] = resultat
            i = i + 1
    print(centersdf)

def findCentroid(data, centers, brand, diesel, electric, petrol, other, hybrid, automatic, manual, otherTr, SemiAuto, year, price, mileage, tax, mpg, engineSize):    #Trouver le centroide le + proche pour un véhicule
    
    #Creation dataframe vehicule selon arguments
    match brand:
        case "brand_audi":
            car = pd.DataFrame(np.array([[1,0,0,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_bmw":
            car = pd.DataFrame(np.array([[0,1,0,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_ford":
            car = pd.DataFrame(np.array([[0,0,1,0,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_merc":
            car = pd.DataFrame(np.array([[0,0,0,1,0,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_toyota":
            car = pd.DataFrame(np.array([[0,0,0,0,1,0,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_vauxhall":
            car = pd.DataFrame(np.array([[0,0,0,0,0,1,0,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])
        case "brand_vw":
            car = pd.DataFrame(np.array([[0,0,0,0,0,0,1,diesel,electric,hybrid,other,petrol,automatic,manual,otherTr,SemiAuto,year,price,mileage,tax,mpg,engineSize]]), columns=["brand_audi","brand_bmw","brand_ford","brand_merc","brand_toyota","brand_vauxhall","brand_vw","Diesel","Electric","Hybrid","Other","Petrol","Automatic","Manual","Other.1","Semi-Auto","year","price","mileage","tax","mpg","engineSize"])

    centersdf = pd.DataFrame(centers, columns=data.columns)
    car = car.astype(float).fillna(0.0)
    
    #Normalisation des données du vehicule
    for dataCol in data.columns:
            max = data[dataCol].max()
            min = data[dataCol].min()
            for carCol in car.columns:
                if(dataCol == carCol):
                    car[carCol][0]=(car[carCol][0]-min)/(max-min)

    #Calcul différence entre chaque centroides et le véhicule
    chosenCentroid = 0
    bigDiff = 9999999
    for iterCentroid in range(len(centersdf.index)):
        print("Centroid : " + str(iterCentroid))
        diff = 0
        for centroidCol in centersdf.columns:
            for carCol in car.columns:
                if(centroidCol == carCol):
                    carValue = float(car[carCol][0])
                    centroidValue = centersdf[centroidCol][iterCentroid]
                    diff = diff + abs(carValue-centroidValue)
        print("Différence entre véhicule et centroide :")
        print(diff)
        if(diff < bigDiff):
            bigDiff = diff
            chosenCentroid = iterCentroid
    #Retourner le centroide le plus proche
    print("Le centroide le plus proche est le centroide : " + str(chosenCentroid))
    return chosenCentroid


def inertie(data):
    # CHARGEMENT DES DONNEES
    X = data
    X.head()

    # pour chaque nombre de clusters, on entraîne un k-means
    # et on enregistre la valeur de l'inertie
    listeInerties = []
    # Pour chaque nombre de clusters (de 1 à 10):
    for k in range(1, 10):
        kmeans = KMeans(n_clusters=k)  # On instancie un k-means pour k clusters
        kmeans.fit(X)  # On entraine
        listeInerties.append(kmeans.inertia_)  # On enregistre l'inertie obtenue

    # GRAPHIQUE POUR CONSTATER LA CASSURE DANS LA COURBE
    # permet de savoir combien de clusters on a besoin
    # il faut regarder les cassures sur la courbe
    fig, ax = plt.subplots(1, 1, figsize=(12, 6))
    ax.set_ylabel("inertie")
    ax.set_xlabel("nombre de cluster")
    ax.set_title("Diagramme")
    ax = plt.plot(range(1, 10), listeInerties)
    plt.show()

def kmeans(X, k, max_iter=1000000):
    # Initialiser aléatoirement les centroids
    centers = X[np.random.choice(X.shape[0], k, replace=False)]

    for i in range(max_iter):
    # --- Assigner chaque point de données au cluster le plus proche
        distance_to_centers = []
        for point in X:
           # calcule la distance de chaque point de données aux centres du cluster
            distances = np.linalg.norm(point - centers, axis=1) #norm = distance Euclidienne
            distance_to_centers.append(distances)
       # on recherche l'index du point ayant la plus petite distance
        min_distance_indexes = np.argmin(distance_to_centers, axis=1) #argmin = calcul de l'index du minimum
       # on le considère comme un nouveau centroid
        labels = min_distance_indexes

    # --- Mettre à jour les centroids en fonction de la moyenne des points de données qui leur sont attribués
        means = []
        for cluster_index in range(k):
            # si c'est un point dans le cluster, on le garde
            cluster_points = X[labels == cluster_index]
            # on fait la moyenne
            cluster_mean = cluster_points.mean(axis=0) #axis=0 indique que la moyenne doit être calculée sur chaque colonne séparément
            means.append(cluster_mean)
        new_centers = np.array(means)

    # --- Si les centroids n'ont pas changé, le clustering est convergé et nous pouvons arrêter l'itération
        if np.allclose(centers, new_centers): #allclose = vérifie su tous les éléments d'un tableau NumPy sont égaux
            break
        centers = new_centers

    # Renvoyer les centroids et les étiquettes de cluster attribuées à chaque point de données
    return centers, labels

    
datas = pd.read_csv('normalisation.csv')
X = datas.values

if (str(sys.argv[1]) == "load") :
    if(len(sys.argv) == 18):
        print("load")

        datas = pd.read_csv('all_e.csv')
        file = open("centroides.sav", 'rb')         #Ouverture du fichier de sauvegarde
        centers = pickle.load(file)                 #chargement de l'arbre de décision
        file.close()                                #Fermeture du fichier

        findCentroid(datas, centers, str(sys.argv[2]), str(sys.argv[3]), str(sys.argv[4]), str(sys.argv[5]), str(sys.argv[6]), str(sys.argv[7]), str(sys.argv[8]), str(sys.argv[9]), str(sys.argv[10]), str(sys.argv[11]), str(sys.argv[12]), str(sys.argv[13]), str(sys.argv[14]), str(sys.argv[15]), str(sys.argv[16]), str(sys.argv[17]))
    else :
        print("Error, worong amount of arguments")
else :
    #Lancer K-means
    centers, labels = kmeans(X, k=3)

    # Tracer les résultats en utilisant les couleurs de Matplotlib pour visualiser les clusters
    #plt.scatter(X[:, 0], X[:, 1], c=labels)

    # Tracer les centroids
    #plt.scatter(centers[:, 0], centers[:, 1], c='red', marker='x')
    #plt.show()

    print("labels : ", labels)
    print("centers : ", centers)
    #denormalisation(datas, centers)
    sauvegarde("centroides.sav", centers)
