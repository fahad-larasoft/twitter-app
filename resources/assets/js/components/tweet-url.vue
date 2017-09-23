<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <div v-if="errors.length" class="alert alert-danger">
                            <ul >
                                <li v-for="error in errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <form @submit.prevent="getFollowers" method="POST">

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="url">Tweet URL</label>
                                    <div class="input-group">
                                        <input v-model="url" id="url" type="text" class="form-control" placeholder="Type here...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary">
                                                Go
                                                <i v-show="!loading" class="fa fa-forward"></i>
                                                <i v-show="loading" class="fa fa-spinner fa-spin"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Retweeting Users</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 item" v-for="user in retweetUsers">
                                <div class="social-feed-container">
                                    <div class="social-feed-element">
                                        <div class="content">
                                            <a class="pull-left">
                                                <img class="media-object" :src="user.profile_image_url">
                                            </a>
                                            <div class="media-body"><p><i class="fa fa-twitter"></i>
                                                <span class="author-title">{{ user.user_name }}</span>
                                            </p>
                                                <div class="text-wrapper">
                                                    <p class="social-feed-text">
                                                        Followers: {{ user.followers_count }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
    .item {
        margin-bottom: 2%;
    }
</style>

<script type="text/babel">
    export default {
        data(){
            return {
                url: '',
                errors: [],
                loading: false,
                retweetUsers: []
            }
        },
        methods: {
            getFollowers(){
                let vue = this;

                var params = {
                    url: vue.url
                };

                vue.loading = true;

                axios.get('/tweet-url/details', {params: params})
                    .then(function (response) {
                        vue.loading = false;
                        vue.retweetUsers = response.data.retweet_users
                    })
                    .catch(function (error) {

                        vue.loading = false;

                        if (error.response) {
                            $.each(error.response.data.errors, function(key, value1){
                                $.each(value1, function(key, value2){
                                    if (vue.errors.indexOf(value2) == -1){
                                        vue.errors.push(value2)
                                    }
                                });
                            });

                            setTimeout(function(){
                                vue.errors = []
                            }, 4000);
                        }
                    });
            }
        }
    }
</script>
