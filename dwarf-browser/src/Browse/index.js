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

export default class Browse {
    state = {
        pages: null,
        greetings: "",
    }

    constructor() {
        let pages;

        Auth.fetch("pages.php", {method: "GET"})
        .then(res => {
            pages = res.pages;
            if (res.loggedInContent)
                this.state.greetings = res.loggedInContent;
        });

        this.state.pages = pages.map((page) => 
            <ComicPage name={page.name} desc={page.description}/>
        );
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