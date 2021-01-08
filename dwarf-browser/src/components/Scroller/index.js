import React from 'react';
import './index.css';
import Auth from '../AuthHelperMethods';
import ComicPage from '../ComicPage.js';

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

        if (this.state.prevY+300 < y) {
            
            const lastPage = this.state.pages[this.state.pages.length - 1];
            const curPage = lastPage.pageId;
            if(this.state.lastPageLoadedId !== curPage){
                this.getPages(curPage);
            }

            this.setState({
                prevY: y,
                lastPageLoadedId: curPage
            });
        }
    }

    getPages = (id) => {

        if(this.state.endReached === false){
            
            this.setState({ loading : true });
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
                    {loading && <p>Chargement ...</p>}
                </div>
            </div>
        )
    }
}