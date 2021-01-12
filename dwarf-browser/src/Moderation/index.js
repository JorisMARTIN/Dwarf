import React from 'react';
import withAuth from '../components/withAuth';
import Auth from '../components/AuthHelperMethods';
import { Redirect } from 'react-router-dom';
import './index.css';
import ComicPage from '../components/ComicPage/ComicPage.js';

class Moderation extends React.Component {
    
    state = {
        isAdmin: false,
        deletedPages: []
    };

    componentDidMount(){
        this.getDeletedPages();
    }

    getDeletedPages = () => {
        Auth.fetch("moderation.php", {
            method: "POST",
            body: JSON.stringify({
                action: "getDeletedPages",
            })
        }).then(res => {
            if(res.isAdmin){
                this.setState({
                    isAdmin: res.isAdmin,
                    deletedPages: res.deletedPages
                });
            }
        });
    }

    render() {
        const {isAdmin, deletedPages} = this.state;
        // if(isAdmin === false){
        //     return <Redirect to="/" />;
        // }else{
            return (
                <div className="moderationMain">
                    <h2>DeletedPages</h2>
                    <div className="moderationDeletedPages">
                        {deletedPages.map((page, i) => (
                            <ComicPage key={i} {...page} isAdmin={false} />
                        ))}
                    </div>
                </div>
            );
        //}
    }
}

export default withAuth(Moderation);