ftp pass: SsR2-5jqY-w4Vy-yFAK-A(Q4-u


eerste test moment.

Routes:


"/" : OK  
"/register"  : OK  
"/login" : OK
"/logout" : OK  
"/admin" : OK - login hardcoded but works  
"/profile" : Read: OK, Write: OK.

Authentication/Errors
User already exists: OK
Ticket status: IN BEHANDELING


mogelijke verbeteringen die weinig tijd kosten. 

Login in cookies onthouden zodat na refresh je ook ingelogd blijft


Maak een verslag met daarin:

• Gebruikte materialen (hardware).
Computer	Schoolcomputer (MAC)

• Gebruikte ontwikkelomgeving.
Visual Studio Code	Code-editor
Plesk / FileZilla	Deployment - bestanden geüpload naar schoolserver via Plesk of FTP
phpMyAdmin (via Plesk)	Database aanmaken en beheren
Composer	PHP dependency management
Git	Versiebeheer van de broncode

• Gebruikte technieken (bijvoorbeeld: programmeertalen).
PHP 8.x	Server-side programmeertaal - routing, business logic, database-interactie, sessies
MySQL	Relationele database - aangemaakt en beheerd via Plesk op de schoolserver
HTML5	Structuur van de Twig-templates
CSS / Bootstrap CDN	Basisopmaak van de gebruikersinterface
Twig	Template engine - strikte scheiding van logica en presentatie
PDO	Database-abstraction layer - veilige queries via prepared statements
FastRoute	URL-routing - koppelt URI-patronen aan controllermethoden
PHP Sessions	Authenticatie - sessie aanmaken bij inloggen, vernietigen bij uitloggen
password_hash()	Wachtwoorden worden gehasht met bcrypt vóór opslag in de database
Composer PSR-4	Autoloading - klassen worden automatisch geladen op basis van namespace
MVC	Architectuurpatroon - scheiding van Model, View en Controller

• Heb je binnen de gestelde tijd een werkend prototype op kunnen leveren.
Yess

Geef aan wat er goed/fout ging.

Twig heeft handled css anders als dat ik had bedacht. Ik had niet gekeken naar hoe je css koppelt alleen naar het functionele deel voor mijn programma. Waardoor ik wat extra tijd kwijt was aan css linken voor dat ik aan het schrijven kon. Omdat ik de bootstrap cdn wel prettig vind maar ook teveel clutter vind geven dacht ik toch terug naar css te gaan om wat van de clutter te verminderen. 

AI
Opdracht 1:
Ai is gebruikt om bepaalde deelen van de documentatie op te schoonen 
Om textuele diagrammen te maken en om .md om naar .docx