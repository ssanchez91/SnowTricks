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
<h2>Installation</h2>
<h3>Environnement nécessaire</h3>
<ul>
  <li>Symfony 5.1.*</li>
  <li>PHP 7.3.*</li>
  <li>MySql 8</li>
</ul>
<h3>Suivre les étapes suivantes :</h3>
<ul>
  <li><b>Etape 1 :</b> Cloner le repository suivant depuis votre terminal :</li>
  <pre>
  <code>git clone https://github.com/ssanchez91/SnowTricks.git</code></pre>     
  <li><b>Etape 2 :</b> Editer le fichier .env </li>
    - pour renseigner vos paramètres de connexion à votre base de donnée dans la variable DATABASE_URL
    - pour renseigner configuration pour l'envois de mail dans la variable MAILER_DSN  
  <li><b>Etape 3 :</b> Démarrer votre environnement local (Par exemple : Wamp Server)</li>
  <li><b>Etape 4 :</b> Exécuter les commandes symfony suivantes depuis votre terminal</li>
  <pre><code>
    symfony console doctrine:database:create (ou php bin/console d:d:c si vous n'avez pas installé le client symfony)<br/>
    symfony console doctrine:migrations:migrate<br/>
    symfony console doctrine:fictures:load  
  </code></pre>
</ul>
  
<h3>Votre site est maintenant installé !</h3>
<p>Pour afficher la page d'accueil de votre site entrer l'url suivante dans votre navigateur : <em>http://yourAdress.domain.fr/home</em></p>
