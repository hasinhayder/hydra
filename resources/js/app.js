const { default: axios } = require('axios');

require('./bootstrap');

const app = {
    init(){
        //do something
    },
    login(){
        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post('/login',{
                'email':'email@example.com',
                'password':'ThisIsABadPassword'
            }).then(response=>{
                location.href='/'
            })
        });
    }
}

window.app = app
app.init()
