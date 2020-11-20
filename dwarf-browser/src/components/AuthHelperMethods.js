import decode from 'jwt-decode';

//adapted from https://github.com/jinolacson/login-jwt-react-php/

class AuthHelperMethods {
    apiURL = "http://dwarf.jorismartin.fr/api/";
    
    // Initializing important variables
    login = (email, password) => {
        // Get a token from api server using the fetch api
        return this.fetch("login.php", {
            method: 'POST',
            body: JSON.stringify({
                email,
                password
            })
        }).then(res => {
            if(res.token !== undefined)
                this.setToken(res.token);
            return Promise.resolve(res.token !== undefined);
        })
    }

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
        .then(this._checkStatus)
        .catch(err => alert(err))
        .then(response => response.json())
    }

    _checkStatus = (response) => {
        // raises an error in case response status is not a success
        if (response.status >= 200 && response.status < 300) { // Success status lies between 200 to 300
            return response
        } else {
            var error = new Error(response.statusText)
            error.response = response
            throw error
        }
    }
}

const Auth = new AuthHelperMethods();
export default Auth;