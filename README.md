# SnowTricks
Projet 6 - OCR - SSANCHEZ

<a href="https://codeclimate.com/github/ssanchez91/SnowTricks/maintainability"><img src="https://api.codeclimate.com/v1/badges/cc71ef5cb784992b2e9a/maintainability" /></a>

<h3>Home Page</h3>

![frontend](https://github.com/ssanchez91/SnowTricks/blob/main/public/assets/img/Readme/Home.PNG)

<h3>Show Trick</h3>

![backend](https://github.com/ssanchez91/SnowTricks/blob/main/public/assets/img/Readme/ShowTrick.PNG)

<h3>Documentation</h3>
<p>L'ensemble du code source a été commenté. L'utilsation de PhpDocumentor a permis de générer une documentation sous forme de site web.</p>
<p>Le site de documentation est accessible à l'url suivante : <em>http://yourAdress.fr/basePath/Docs/index.html</em></p>

<h3>Diagrammes UML</h3>
Les schémas UML se situent dans le dossier diagrammes à la racine du projet:
<ul>
  <li>Diagramme de classe</li>
  <li>Diagramme de cas d'utilsation</li>
  <li>Diagramme de séquence</li>
  <li>MPD</li>
</ul>  
Les fonctinnalités décrites dans les diagrammes concernent les figures, les commentaires et les utilisateurs.

<h3>Langage de programmation</h3>

<ul>
</ul>
<li>Le site a été développé en PHP via le framework Symfony 5.1.</li>
<li>Le framework Bootstrap a été utilisé pour l'affichage des vues.<a href="https://getbootstrap.com/" target="_blank"> Plus d'informations</a></li>
<li>La librairie JQuery a été utilisé pour coder les parties nécessitant l'intervention de code JavaScript.</li>
<hr>
<h2>Installation</h2><br>
<ul>
  <li><b>Etape 1 :</b> Copier les fichiers dans le dossier racine de votre serveur web (en général "www/").</li>
  <li><b>Etape 2 :</b> Créer une base données sur votre SGDB (MySQL) et importer le fichier DB/blog.sql pour créer les tables du blog.</li>
  <li><b>Etape 3 :</b> Dans le fichier Config/config.json, modifier les paramètres dans la section <b>dataBase:</b></li>
</ul>

<h4>dataBase:</h4>
    <p>{
      "host": "localhost",<br>
      "dbname": "yourDataBaseName",<br>
      "user": "yourLogin",<br>
      "password": "yourPassword"<br>
  }</p>

<b>Important</b>
 Veuillez à bien remplir tout les champs avec vos informations de la même façon que celle fournit dans l'exemple !

<ul>
  <li><b>Etape 4 :</b> Dans le fichier Config/config.json, modifier les paramètres dans la section <b>mailManager:</b></li>
</ul>
<h4>mailManager:</h4>
    <p>{
      "mailTo": "yourAdresse@email.fr ",<br>
      "mailFrom": "noreply@domain.fr"<br>   
  }</p>
  
  <ul>
  <li><b>Etape 5 :</b> Dans le fichier Config/config.json, modifier les paramètres dans la section <b>basePath:</b></li>
</ul>
<h4>basePath:</h4>
    <p>"/Blog"<em> Enter <strong>"/"</strong> and your <strong>base directory name</strong> </em></p> 
  
<h3>Votre Blog est maintenant installé !</h3>
<p>Pour afficher la page d'accueil de votre Blog entrer l'url suivante dans votre navigateur : <em>http://yourAdress.fr/basePath/default</em></p>
<hr>
<h2>Créer un compte utilisateur</h2><br>
<ul>
  <li>Rendez-vous sur l'url suivante : <em>http://yourAdress.fr/basePath/registerUser</em></p></li><br> 
  <li>Renseigner les informations demandées dans le formulaire et cliquer sur le bouton pour valider l'inscription.</li><br>
</ul>
<hr>
<h2>Obtenir un compte Admin</h2><br>
<ul>
  <li>Dans votre base de données, dans la table "user", récupérer <b>l'id du user</b> que vous venez de créer.
  <li>Dans votre base de données, dans la table "role_user", ajouter les deux lignes suivante dans l'editeur de requête SQL :
    <ul>
      <li> INSERT INTO `role_user`(`user_id`, `role_id`) VALUES (<b>your userId</b>,<b>1</b>); <em>Pour vous donner le role Admin</em> </li><br>
      <li> INSERT INTO `role_user`(`user_id`, `role_id`) VALUES (<b>your userId</b>,<b>3</b>); <em>Pour vous donner le role Auteur</em> </li><br>
    </ul>  
  <li>et éxécuter la requête.</li><br>
</ul>
<p>Vous disposez désormais d'un compte administrateur qui vous permet de gérer votre blog via l'interface d'administration accessible en utilisant la route suivante :</p>
<ul>
  <li><em>http://yourAdress.fr/basePath/admin</em>
  </li>
</ul>  
