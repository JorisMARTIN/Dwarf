import { Component } from 'react';
import './index.css';

export default class Help extends Component {
    render(){
        return(
            <div className="help">
                <h1 className="helpTitle">Aide</h1>
                <section className="helpMain">
                    <article className="helpWhatIsIt">
                        <h2>Qu'est ce que DWARF ?</h2>
                        <p>DWARF est un jeu de dessin collaboratif.<br/>
                        Ce jeu consiste à créer des planches de bande dessinées à plusieurs. 
                        Une personne va créer la planche en lui donnant un titre et une description où il pourat exprimer ce que doit raconter sa BD
                         et une fois cela fait il pourat dessiner la première case<br/>
                        Par la suite n'importe qui pourat venir réaliser la suite de la BD en n'ayant que pour contexte la derniere case dessinée.</p>

                        <p>Sur la page d'accueil, tu pouras retrouver les planches de BD fini.</p>
                    </article>
                    <article className="helpDraw">
                        <h2>Dessiner !</h2>
                        <p>Tu peux dessiner la suite d'une BD initier par un autre utilisateur. Pour cela tu doit être connecter !<br/>
                        Tu aura alors de nombreux outils à ta disposition :</p>
                        <ul>
                            <li>Des couleurs</li>
                            <li>La taille de ton crayon</li>
                            <li>Un astiant de tracage de courbe</li>
                            <li>La case précédente</li>
                        </ul>
                        <p>Noublie pas de soumettre ton dessin !</p>
                    </article>
                    <article className="helpInit">
                        <h2>Nouvelle BD</h2>
                        <p>Vous pouvez y initier une nouvelle bande dessinée,<br/>
                        Vous devez rentrez plusieurs paramètres :</p>
                        <ul>
                            <li>Le nom de votre planche</li>
                            {/* <li>Le type de jeu :</li>
                            <li>
                                <li>Normal : Tu commence par dessiner la première case en haut à gauche pour finir vers la case en bas à droite</li>
                                <li>Reverse : Tu commence par dessiner la première case en bas à droite pour finir vers la case en haut à gauche</li>
                                <li>Intermediate : Tu commence par dessiner la case centrale et puis ....</li>
                            </li> */}
                            <li>Une petite description du thème global de votre BD, des contraintes ...</li>
                            {/* <li>Le choix du mode de visibilité :</li>
                            <li>
                                <li>Public : Tout les utilisateurs peuvent compléter la planche</li>
                                <li>Private : Seul les utilisateurs disposant du lien ont accès à la planche</li>
                            </li> */}
                            <li>Le choix d'un template parmis les 3 proposés afin de choisir le modèle de BD</li>
                        </ul>
                    </article>
                    <article className="helpConnect">
                        <h2>Se connecter/S'inscrire</h2>
                        <p>Connecte toi avec ton compte,<br/>
                        ou alors créer toi un compte en donnant ton pseudo, ton adresse email, ta date de naissance, ainsi qu'un mot de passe.</p>
                    </article>
                </section>
            </div>
        );
    }
}