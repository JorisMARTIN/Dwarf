import './index.css';
import withAuth from '../components/withAuth';

function Draw() {
    return (
        <div>
            <h1>Draw</h1>
        </div>
    );
}

export default withAuth(Draw);