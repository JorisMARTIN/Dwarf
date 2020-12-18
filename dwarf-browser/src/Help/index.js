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
                        Une personne va créer une planche en lui donnant un titre et une description où il pourra exprimer ce que doit raconter sa bande dessinée,
                         et une fois cela fait, il pourra dessiner la première case.<br/>
                        Par la suite n'importe qui peut venir réaliser la suite de la bande dessinée en n'ayant pour contexte que la dernière case dessinée !</p>

                        <p>Sur la page d'accueil, tu pourras retrouver les planches de bande dessinées finies.</p>
                    </article>
                    <article className="helpDraw">
                        <h2>Dessiner !</h2>
                        <p>Tu peux dessiner ici la suite d'une BD commencée par un autre utilisateur. Pour cela, tu dois être connecté(e) !<br/>
                        Tu auras alors de nombreux outils à ta disposition :</p>
                        <ul>
                            <li>Des couleurs</li>
                            <li>La taille de ton crayon</li>
                            <li>Un assistant de traçage de courbe</li>
                            <li>La case précédente</li>
                        </ul>
                        <p>N'oublie pas de soumettre ton dessin !</p>
                    </article>
                    <article className="helpInit">
                        <h2>Nouvelle BD</h2>
                        <p>Tu peux commencer ici une nouvelle bande dessinée,<br/>
                        Pour se faire, tu dois saisir :</p>
                        <ul>
                            <li>Le nom de ta planche</li>
                            {/* <li>Le type de jeu :</li>
                            <li>
                                <li>Normal : Tu commence par dessiner la première case en haut à gauche pour finir vers la case en bas à droite</li>
                                <li>Reverse : Tu commence par dessiner la première case en bas à droite pour finir vers la case en haut à gauche</li>
                                <li>Intermediate : Tu commence par dessiner la case centrale et puis ....</li>
                            </li> */}
                            <li>Une petite description du thème global de ta bande dessinée, des contraintes ...</li>
                            {/* <li>Le choix du mode de visibilité :</li>
                            <li>
                                <li>Public : Tout les utilisateurs peuvent compléter la planche</li>
                                <li>Private : Seul les utilisateurs disposant du lien ont accès à la planche</li>
                            </li> */}
                            <li>Le choix d'un modèle de bande dessinée parmi les 3 proposés</li>
                        </ul>
                    </article>
                    <article className="helpConnect">
                        <h2>Se connecter / S'inscrire</h2>
                        <p>Connecte toi avec ton compte,<br/>
                        ou alors crée toi un compte en donnant ton pseudo, ton adresse email, ta date de naissance, ainsi qu'un mot de passe.</p>
                    </article>
                </section>
            </div>
        );
    }
}