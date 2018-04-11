import Vue from 'vue';
import axios from 'axios';

var app = new Vue({
    el: '#app',
    data: {
        errors: [],
        encodingUrl: '',
        shortUrls: [],
    },

    methods: {
        shortify: function () {
            const $this = this;
            if(!this.encodingUrl) {
                this.errors = [];
                this.errors.push("Поле Url не должно быть пустым");
            }
            else {
                axios.post('/shortify/encode', {
                    encodingUrl: this.encodingUrl
                })
                    .then(function (response) {
                        console.log(response.data);
                        if(response.data.exist != true) {
                            $this.shortUrls.push(response.data.shortUrl);
                        } else {
                            $this.errors.push("Такая ссылка уже существует в базе: " + response.data.shortUrl);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                this.errors = [];
            }
            this.encodingUrl = '';
        }
    }
})