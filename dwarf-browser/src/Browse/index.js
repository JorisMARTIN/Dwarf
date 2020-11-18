import { Component } from 'react';
import './index.css';
import Auth from '../components/AuthHelperMethods';

function ComicPage(props) {
    return (
        <div>
            <h3>{props.name}</h3>
            <p>{props.desc}</p>
        </div>
    );
}

export default class Browse extends Component {
    state = {
        pages: null,
        greetings: "",
    }

    componentDidMount() {
        let pages;

        Auth.fetch("pages.php", {method: "GET"})
        .then(res => {
            pages = res.pages;
            if (res.loggedInContent) {
                this.setState({ greetings: res.loggedInContent});
            }
            
            console.log(res);
        }).then(() => {
            this.setState({pages: pages.map((page, i) =>
                <ComicPage key={i} name={page.name} desc={page.description} />
            )});
        });
    }

    render() {
        return (
            <div>
                <h1>Browse drawings</h1>
                {this.state.greetings && <h2>{this.state.greetings}</h2>}
                {this.state.pages}
            </div>
        );
    }
}