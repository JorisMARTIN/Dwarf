import React from 'react';
import './index.css';
import InfiniteScroll from 'react-infinite-scroll-component';
import Auth from '../components/AuthHelperMethods';

class ComicPage extends React.Component {
    constructor(props) {
        super(props);
        this.canvasRef = React.createRef();
    }

    state = {
        canvasW: 0,
        canvasH: 0
    }

    async componentDidMount() {
        const canvas = this.canvasRef.current;
        const template = await (await fetch('http://dwarf.jorismartin.fr/cdn/templates/template' + this.props.template + '.json')).json();
        const ctx = canvas.getContext('2d');

        let max_x = 0;
        let max_y = 0;

        for (const frame of template) {
            if(frame.x + frame.w > max_x) max_x = frame.x + frame.w;
            if(frame.y + frame.h > max_y) max_y = frame.y + frame.h;
        }

        this.setState({
            canvasW: max_x,
            canvasH: max_y
        });

        //todo : charger les templates de maniere lazy et pas recalculer pour chaque page !=

        for(const i in this.props.images) {
            const image = new Image();
            image.src = 'http://dwarf.jorismartin.fr' + this.props.images[i];
            image.onload = () => {
                ctx.drawImage(image, template[i].x, template[i].y, template[i].w, template[i].h);
            }
        }
    }

    render() {
        return (
            <div className="homePlanche">
                <div className="homePlancheTop">
                    <canvas className="homePlancheImg" ref={this.canvasRef} width={this.state.canvasW} height={this.state.canvasH} />
                    <div className="homePlancheTopInfos">
                        <textarea readOnly disabled className="homeName" value={this.props.name} />
                        <textarea readOnly disabled className="homeDescri" value={this.props.description} />
                        <p className="homeMode">{this.props.gamemode}</p>
                        <p className="homeUser">Auteur : {this.props.user}</p>
                    </div>
                </div>
                <div className="homePlancheBottom">
                    <button type="button">Like</button>
                    <button type="button">Dislike</button>
                </div>
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
                    <ComicPage key={i + this.state.pages.length} {...page} />
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
                <h1 className="homeTitle">Home</h1>
                <div className="homeMain">
                    <InfiniteScroll
                        className="homeDivPlanche"
                        dataLength={this.state.pages.length}
                        next={this.fetchMoreData}
                        hasMore={this.state.hasMoreData}
                        loader={<h4 className="homeDivPlancheLoader">Loading ...</h4>}
                        endMessage={<p className="homeDivPlancheLoaderEnd">You have seen all the comics</p>}
                    >
                        {this.state.pages}
                    </InfiniteScroll>
                </div>
            </div>
        );
    }
}