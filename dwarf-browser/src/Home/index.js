import React from 'react';
import './index.css';
import { Redirect } from 'react-router-dom';
import InfiniteScroll from 'react-infinite-scroll-component';
import Auth from '../components/AuthHelperMethods';

class ComicPage extends React.Component {
    constructor(props) {
        super(props);
        this.canvasRef = React.createRef();

        this.authors = "Authors :\n";
        for (const author of this.props.authors) this.authors += author + "\n";
    }

    state = {
        canvasW: 0,
        canvasH: 0,
        redirectVote: false,
        fullscreen: false,
        rate: null,
    }

    async componentDidMount() {
        const canvas = this.canvasRef.current;
        const template = await (await fetch('https://dwarf.jorismartin.fr/cdn/templates/template' + this.props.template + '.json')).json();
        const ctx = canvas.getContext('2d');

        let max_x = 0;
        let max_y = 0;

        for (const frame of template) {
            if (frame.x + frame.w > max_x) max_x = frame.x + frame.w;
            if (frame.y + frame.h > max_y) max_y = frame.y + frame.h;
        }

        this.setState({
            canvasW: max_x,
            canvasH: max_y
        });

        //todo : charger les templates de maniere lazy et pas recalculer pour chaque page !=

        for (const i in this.props.images) {
            const image = new Image();
            image.src = 'https://dwarf.jorismartin.fr' + this.props.images[i];
            image.onload = () => {
                ctx.drawImage(image, template[i].x, template[i].y, template[i].w, template[i].h);
            }
        }
    }

    handleVoteClick = (rate) => {
        this.setState({ rate: rate });

        Auth.fetch("rate.php", {
            method: "POST",
            body: JSON.stringify({
                pageId: this.props.pageId,
                rateType: rate
            })
        }).then(res => {
            if (res.userId) {
                // Display message to the user
                alert(res.message);
            } else {
                // User not loged In
                if (window.confirm(res.message)) {
                    this.setState({
                        redirectVote: true
                    })
                }
            }
        })
    }

    toggleFullscreen = () => {
        if (this.state.fullscreen) {
            this.setState({ fullscreen: false });
        } else {
            this.setState({ fullscreen: true });
        }
    }

    deletePage = () => {
        //const pageIdToDelete = this.props.pageId;
        if (window.confirm("Do you realy want to delete this page ?")) {
            alert("Et non ca marche pas encore !");
        }
    }

    render() {
        if (this.state.redirectVote) return <Redirect to="/auth" />
        else return (
            <div className="homePlancheWrapper">
                <div className={`homePlanche ${this.state.rate === 1 && "plancheLike"} ${this.state.rate === 0 && "plancheDislike"}`}>
                    <div className="homePlancheTop">
                        <canvas onClick={this.toggleFullscreen} title={this.authors} className="homePlancheImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                        <div className="homePlancheTopInfos">
                            <textarea readOnly disabled className="homeName" value={this.props.name} />
                            <textarea readOnly disabled className="homeDescri" value={this.props.description} />
                            <p className="homeMode">{this.props.gamemode}</p>
                            <p className="homeUser" title={this.authors}>Auteur : {this.props.user}</p>
                        </div>
                    </div>
                    {/* <div className="homePlancheBottom">
                        <button type="button" onClick={() => this.handleVoteClick(1)}>Like</button>
                        <button type="button" onClick={() => this.handleVoteClick(0)}>Dislike</button>
                    </div> */}
                </div>
                {/* {this.props.userIsAdmin && <button className="homeDeleteAdminButton" type="button" onClick={this.deletePage}>Delete</button>} */}
            </div>
        );
    }
}

export default class Home extends React.Component {

    state = {
        pages: [],
        lastPageLoadedId: -1,
        hasMoreData: true,
    }

    componentDidMount() {
        this.loadComponents();
    }

    loadComponents = () => {
        Auth.fetch("home.php", {
            method: "POST",
            body: JSON.stringify({
                lastPageLoadedId: this.state.lastPageLoadedId,
            })
        }).then(res => {
            this.setState({
                pages: this.state.pages.concat(res.pages.map((page, i) =>
                    <ComicPage key={i + this.state.pages.length} {...page} userIsAdmin={res.userIsAdmin} />
                )),
                lastPageLoadedId: res.lastPageLoadedId,
                hasMoreData: !res.endReached,
            });
        })
    }

    fetchMoreData = () => {
        if (this.state.hasMoreData)
            this.loadComponents();
    }

    render() {
        return (
            <div className="homeAll">
                <h1 className="homeTitle">Accueil</h1>
                <div className="homeMain">
                    <InfiniteScroll
                        className="homeDivPlanche"
                        dataLength={this.state.pages.length}
                        next={this.fetchMoreData}
                        hasMore={this.state.hasMoreData}
                        loader={<h4 className="homeDivPlancheLoader">Chargement ...</h4>}
                    // endMessage={<p className="homeDivPlancheLoaderEnd">You have seen all the comics</p>}
                    >
                        {this.state.pages}
                    </InfiniteScroll>
                </div>
            </div>
        );
    }
}