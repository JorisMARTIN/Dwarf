import './index.css';
import withAuth from '../components/withAuth';

function InitDrawing() {
    return (
        <div>
            <h1>initiate a drawing</h1>
        </div>
    );
}

export default withAuth(InitDrawing);