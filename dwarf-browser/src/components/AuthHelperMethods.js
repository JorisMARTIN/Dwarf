import decode from 'jwt-decode';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

class AuthHelperMethods {
    apiURL = "http://dwarf.jorismartin.fr/api/";

    loggedIn = () => {
        // Checks if there is a saved token and it's still valid
        const token = this.getToken();
        return !!token && this.isTokenValid(token);
    }

    isTokenValid = (token) => {
        try {
            const decoded = decode(token);
            return decoded.exp > Date.now() / 1000; //check expiration date
        }
        catch (err) {
            console.log("token check failed !");
            console.log(err);
            //asume token is expired and logout
            this.logout();
            return false; 
        }
    }

    setToken = (idToken) => {
        // Saves user token to localStorage
        localStorage.setItem('id_token', idToken)
    }

    getToken = () => {
        // Retrieves the user token from localStorage
        return localStorage.getItem('id_token')
    }

    logout = () => {
        // Clear user token and profile data from localStorage
        localStorage.removeItem('id_token');
    }

    fetch = (endpoint, options) => {
        // performs api calls sending the required authentication headers
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
        // Setting Authorization header
        // Authorization: Bearer xxxxxxx.xxxxxxxx.xxxxxx
        if (this.loggedIn()) {
            headers['Authorization'] = 'Bearer ' + this.getToken()
        }
        
        return fetch(this.apiURL + endpoint, {
            headers,
            ...options
        })
        .then(res => {
            if (res.status >= 200 && res.status < 300) { // Success status lies between 200 to 300
                return res
            } else {
                alert(res);
            }
        })
        .then(response => response.json())
        .catch(alert)
    }
}

const Auth = new AuthHelperMethods();
export default Auth;