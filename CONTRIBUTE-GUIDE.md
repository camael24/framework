Guide de contribution
======

Nous avons adopt� le mode de fonctionnement par Pull Request
adapt� a notre h�bergeur [Github](http://github.com/)

Dans ce guide nous allons d�taill� pas � pas toutes les �tapes pour contribuer.
Nous partons du principe que vous savez utiliser Github, et que vous avez fait
un fork du [d�pot](http://github.com/sohoa/framework) concern� et que vous
travaill� dans le clone de votre fork ie git clone http://github.com/<votre_username>/framework && cd framework`


Important
=====

Ne jamais travailler sur la branche `master` est votre devoir travaill� sur votre branche est votre droit.

Ajout des remotes
-----

Par d�faut le remote `origin` pointe sur votre d�pot github (http://github.com/<votre_username>/framework).
Nous allons ajouter un remote vers le d�pot sohoa/sohoa afin de pouvoir se synchroniser et r�cuperer les diff�rentes 
modifications apport�es sur le d�pot durant votre developpement.

```
git remote add sohoa https://github.com/sohoa/framework
```

Mise � jour de votre d�pot avec les derni�res modification
-----

```
git pull sohoa master
```

Cette commande peut engendrer des conflits si jamais vous n'avez pas suivi ce guide

Contributions
=====

Mon premier jet de code
-----

Pour des raisons de pratiques j'ai adopt� la nomination des branches suivantes `f/<ma_feature>` dans le cadre
d'une nouvelle feature et `b/<mon_bugfix>` pour la r�solution d'un bug, mais ce n'est qu'une pratique et non pas une obligation

```
git checkout -b <f/maNouvelleSuperFeature>
// Modify your code for your next feature
git commit -a -m "My first feature"
//Modify
git commit -a -m "fix an little bug"
//Modify
git commit -a -m "Be compatible with PSR"
//...

git push origin <f/maNouvelleSuperFeature>
```

Dans l'interface de github quand vous pensez votre feature pr�te vous la poussez, ainsi le serveur 
d'int�gration continue en sera inform� et jouera les tests (unitaires et de conformit� a PSR) automatiquement
et le rapport apparaitra sous peu (~ 5-10 minutes suivant la disponibilit� du robot) dans votre PSR

Discussion
-----

Avant de merger la PR probablement nous en discuterons en interne (sur IRC et/ou sur la ML de sohoa), des pr�cisions
peuvent �tre demand�es, et / ou des compl�ments de code pour cela la marche � suivre est celle du paragraphe �Mon premier jet de code.


Acceptation
=====

Dans le cas de la validation de la PR nous serons amenez a effectuer une ultime manipulation sur la PR.
En effet nous nous basons sur cette [page](http://github.com/sohoa/framework/network) et votre PR (Branche) doit correspondre � deux crit�res
*	Avoir comme commit parent le dernier commit
*	Avoir qu'un seul et unique commit

Mettre � jour notre branche
-----

Nous allons mettre � jour notre banch avec les donn�es contenues dans master

```
git checkout master
git pull sohoa master
git log  --abbrev-commit -n 1
```
On obtient le <sha1> du dernier commit

``` 
git checkout <f/maNouvelleSuperFeature>
git rebase <sha1>
```

On peut obtenir des conflits que l'on doit r�soudre et faire des commits comme vu dans le  �Mon premier jet de code


Un seul commit
-----

Nous allons effectuer un rebase int�ractif sur notre branch.
Pour cela nous allons suivre un exemple :

```
git log --abbrev-commit
git rebase -i <sha1>
```

##### Rebase Interactif


Edit , Fixup ...





