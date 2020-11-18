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

export default class Home extends Component {
    state = {
        pages: null,
    }

    componentDidMount() {
        Auth.fetch("home.php", { method: "GET" })
            .then(res => {
                this.setState({
                    pages: res.pages.map((page, i) =>
                        <ComicPage key={i} name={page.name} desc={page.description} />
                    )
                });
            })
    }

    render() {
        return (
            <div>
                <h1>Home</h1>
                {this.state.pages}
            </div>
        );
    }
}