import { Component } from 'react';
import './index.css';
import InfiniteScroll from 'react-infinite-scroll-component';
import Auth from '../components/AuthHelperMethods';

function ComicPage(props) {
    return (
        <div className="homePlanche">
            <div className="homePlancheTop">
                <img className="homePlancheImg" src={"http://dwarf.jorismartin.fr" + props.imagePtr} alt={props.name}></img>
                <div>
                    <h3 className="homeName">Titre : {props.name}</h3>
                    <p className="homeDescri">Description :<br></br>
                        {props.description}</p>
                    <p className="homeMode">Mode :<br></br>
                        {props.gamemode}</p>
                </div>
            </div>
            <div className="homePlancheBottom">
                <button type="button">Like</button> 
                <button type="button">Dislike</button> 
            </div>
        </div>
    );
}

export default class Home extends Component {
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
                    <ComicPage key={i + this.state.pages.length} {...page}/>
                )),
                lastPageLoadedId: res.lastPageLoadedId,
                hasMoreData: !res.endReached,
            });
        })
    }

    fetchMoreData = () => {

        if(!this.state.hasMoreData) return;
        
        setTimeout(() => {
            this.loadComponents()
        }, 500);

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
                        loader={<h4>Loading ...</h4>}
                        endMessage={<p>You have seen all the pages</p>}
                    >
                        {this.state.pages}
                    </InfiniteScroll>
                </div>
            </div>
        );
    }
}