// resources/js/bootstrap.js

// Importez les bibliothèques nécessaires ici, par exemple Axios ou jQuery
import _ from 'lodash';
window._ = _;

// Exemple: Importer Axios
import axios from 'axios';
window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Vous pouvez ajouter d'autres configurations ou bibliothèques ici
