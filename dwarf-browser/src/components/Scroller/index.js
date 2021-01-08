import React from 'react';
import './index.css';
import { Redirect } from 'react-router-dom';
import Auth from '../AuthHelperMethods';

class ComicPage extends React.Component {
    constructor(props) {
        super(props);
        this.canvasRef = React.createRef();

        const authors = [...this.props.authors.reduce((p, c) => p.set(c, true), new Map()).keys()];
        this.authors = "Auteur" + (authors.length > 1 ? "s" : "") + " : ";
        this.authorsTitle = this.authors + "\n";

        for (const author of authors) {
            this.authors += author + ", ";
            this.authorsTitle += author + "\n";
        }

        this.authors = this.authors.slice(0, -2);
    }

    state = {
        canvasW: 0,
        canvasH: 0,
        redirectVote: false,
        fullscreen: false,
        rate: null
    }

    componentDidMount() {
        this.drawPlanche();
    }

    drawPlanche = async () => {
        const canvas = this.canvasRef.current;
        const template = await (await fetch(Auth.url + '/cdn/templates/template' + this.props.template + '.json')).json();
        const ctx = canvas.getContext('2d');

        let max_x = 0;
        let max_y = 0;

        for (const frame of template) {
            if (frame.x + frame.w > max_x) max_x = frame.x + frame.w;
            if (frame.y + frame.h > max_y) max_y = frame.y + frame.h;
        }

        const ccbyncX = 240;
        const ccbyncY = 84;

        max_y += ccbyncY; // space for cc by nc

        this.setState({
            canvasW: max_x,
            canvasH: max_y
        });

        //todo : charger les templates de maniere lazy et pas recalculer pour chaque page !=

        for (const i in this.props.images) {
            const image = new Image();
            image.src = Auth.url + this.props.images[i];
            image.onload = () => {
                ctx.drawImage(image, template[i].x, template[i].y, template[i].w, template[i].h);
            }
        }

        const ccbync = new Image();
        ccbync.src = Auth.url + '/icons/cc-by-nc.svg';
        ccbync.onload = () => {
            ctx.drawImage(ccbync, max_x - ccbyncX, max_y - ccbyncY, ccbyncX, ccbyncY);
        }
    }

    /* VOTE */

    handleVoteClick = (rate) => {
        this.setState({ rate: rate });
        Auth.fetch("rate.php", {
            method: "POST",
            body: JSON.stringify({
                pageId: this.props.pageId,
                rateType: rate
            })
        }).then(res => {
            if (res.message) {
                // User not loged In
                if (window.confirm(res.message)) {
                    this.setState({
                        redirectVote: true
                    })
                }
            }
        })
    }

    /* Agrandissement Page */

    toggleFullscreen = () => {
        if (this.state.fullscreen) {
            this.setState({ fullscreen: false });
        } else {
            this.setState({ fullscreen: true });
        }
    }

    componentDidUpdate() {
        this.drawPlanche();
    }
    /* Supression page */

    deletePage = () => {
        if (window.confirm("Do you realy want to delete this page ?")) {
            const reason = window.prompt("Please mention the reason of the detele : ");
            if(reason){
                Auth.fetch("delete.php", {
                    method: "POST",
                    body: JSON.stringify({
                        pageId: this.props.pageId,
                        action: "delete",
                        reason: reason
                    })
                }).then(res => {
                    if (window.confirm(res.message + "\nConfirm to continue : ")) {
                        window.location.reload();
                    }
                })
            }
        }
    }

    render() {
        if (this.state.redirectVote) return <Redirect to="/auth" />
        else if (this.state.fullscreen) return (
            <div className="homePlancheFullscreenWrapper">
                <div className={`homePlancheFullscreen ${this.state.rate === 1 ? "plancheLike" : ""} ${this.state.rate === 0 ? "plancheDislike" : ""}`}>
                    <canvas onClick={this.toggleFullscreen} title={this.authorsTitle} className="homePlancheFullscreenImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                    <div className="homePlancheTopInfos homePlancheFullscreenInfos">
                        <textarea readOnly disabled className="homeName" value={this.props.name} />
                        <textarea readOnly disabled className="homeDescriFullscreen" value={this.props.description} />
                        <p className="homeMode">{this.props.gamemode}</p>
                        <p className="homeUser">{this.authors}</p>
                    </div>
                </div>
            </div>
        );
        else return (
            <div className="homePlancheWrapper">
                <div className={`homePlanche ${this.state.rate === 1 ? "plancheLike" : ""} ${this.state.rate === 0 ? "plancheDislike" : ""}`}>
                    <div className="homePlancheTop">
                        <canvas onClick={this.toggleFullscreen} title={this.authorsTitle} className="homePlancheImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                        <div className="homePlancheTopInfos">
                            <textarea readOnly disabled className="homeName" value={this.props.name} />
                            <textarea readOnly disabled className="homeDescri" value={this.props.description} />
                            <p className="homeMode">{this.props.gamemode}</p>
                            <p className="homeUser">{this.authors}</p>
                        </div>
                    </div>
                    <div className="homePlancheBottom">
                        <button type="button" onClick={() => this.handleVoteClick(1)}>Like</button>
                        <button type="button" onClick={() => this.handleVoteClick(0)}>Dislike</button>
                    </div>
                </div>
                {this.props.userIsAdmin && <button className="homeDeleteAdminButton" type="button" onClick={this.deletePage}>Delete</button>}
            </div>
        );
    }
}

export default class Scroller extends React.Component {
    state = {
        pages: [],
        loading: false,
        lastPageLoadedId: -1,
        prevY: 0,
        endReached: false,
        userIsAdmin: false
    };

    componentDidMount(){
        this.getPages(this.state.lastPageLoadedId);

        window.addEventListener('scroll', this.handleScroll);
    }

    componentWillUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    }

    handleScroll = () => {
        const y = window.scrollY;

        if (this.state.prevY + 300 < y) {

            const lastPage = this.state.pages[this.state.pages.length - 1];
            const curPage = lastPage.pageId;
            if (this.state.lastPageLoadedId !== curPage) {
                this.getPages(curPage);
            }

            this.setState({
                prevY: y,
                lastPageLoadedId: curPage
            });
        }
    }

    getPages = (id) => {

        if (this.state.endReached === false) {

            this.setState({ loading: true });
            Auth.fetch("home.php", {
                method: "POST",
                body: JSON.stringify({
                    lastPageLoadedId: id,
                })
            }).then(res => {
                this.setState({ 
                    loading : false,
                    endReached: res.endReached,
                    userIsAdmin: res.userIsAdmin
                 });
                this.setState({ pages: this.state.pages.concat(res.pages)})
            })
        }

    }

    render(){
        const {pages, loading, userIsAdmin} = this.state;
        return(
            <div className="scrollerMain">
                <div className="scrollerContainer">
                    {pages.map((page,i) => (
                        <ComicPage key={i} {...page} userIsAdmin={userIsAdmin} />
                    ))} 
                </div>
                <div>
                    {loading && <p class="scrollerLoading">Chargement ...</p>}
                </div>
            </div>
        )
    }
}