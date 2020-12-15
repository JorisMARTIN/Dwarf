import { Component } from 'react';
import './index.css';

export default class Help extends Component {
    render(){
        return(
            <div className="help">
                <section className="helpMain">
                    <h1>Aide</h1>
                    <article className="helpWhatIsIt">
                        <h2>Qu'est ce que DWARF ?</h2>
                        <p>DWARF est une application de dessin collaboratif.</p>
                    </article>
                    <article className="helpDraw">
                        <h2>Dessiner !</h2>
                        <p>Tu peux dessiner la suite d'une BD initier par un autre utilisateur. Pour cela tu doit être connecter !</p><br></br>
                        <p>Tu aura alors de nombreux outils à ta disposition :</p>
                        <li>
                            <lu>Des couleurs</lu>
                            <lu>La taille de ton crayon</lu>
                            <lu>Un astiant de tracage de courbe</lu>
                            <lu>La case précédente</lu>
                        </li>
                        <p>Noublie pas de soumettre ton dessin !</p>
                    </article>
                    <article className="helpInit">
                        <h2>Nouvelle BD</h2>
                        <p>Vous pouvez y initier une nouvelle bande dessiner.</p><br></br>
                        <p>Vous devez rentrez plusieurs paramètres :</p>
                        <li>
                            <lu>Le nom de votre planche</lu>
                            {/* <lu>Le type de jeu :</lu>
                            <li>
                                <lu>Normal : Tu commence par dessiner la première case en haut à gauche pour finir vers la case en bas à droite</lu>
                                <lu>Reverse : Tu commence par dessiner la première case en bas à droite pour finir vers la case en haut à gauche</lu>
                                <lu>Intermediate : Tu commence par dessiner la case centrale et puis ....</lu>
                            </li> */}
                            <lu>Une petite description du thème global de votre BD, des contraintes ...</lu>
                            {/* <lu>Le choix du mode de visibilité :</lu>
                            <li>
                                <lu>Public : Tout les utilisateurs peuvent compléter la planche</lu>
                                <lu>Private : Seul les utilisateurs disposant du lien ont accès à la planche</lu>
                            </li> */}
                            <lu>Le choix d'un template parmis les 3 proposés afin de choisir le modèle de BD</lu>
                        </li>
                    </article>
                    <article className="helpConnect">
                        <h2>Se connecter/S'inscrire</h2>
                        <p>Connecte toi avec ton compte.</p><br></br>
                        <p>Ou alors créé toi un compte en donnant ton pseudo, ton adresse email, ta date de naissance, ainsi qu'un mot de passe.</p>
                    </article>
                </section>
            </div>
        );
    }
}