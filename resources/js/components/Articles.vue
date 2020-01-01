<template>
    <div>
        <hr>
        <h3>Articles</h3>

        <div class="row">
            <div class="col-md-4">
                <form v-on:submit.prevent="addArticle" class="mb-3" action="" method="post">
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="article.title" name="title" id="title" placeholder="Article Title">
                    </div>                    
                    <div class="form-group">
                        <textarea class="form-control" type="text" v-model="article.body" name="body" id="body" placeholder="Article Body"></textarea>
                    </div>
                    <button class="btn btn-primary btn-block" type="submit">Add Article</button>    
                </form>
            </div>
        </div>
        <hr>
        <nav aria-label="Page navigation example">
            <ul class="pagination mb-3">
                <li v-bind:class="[{disabled: !pagination.prevPageUrl}]" class="page-item">
                    <a href="#" v-on:click="fetchArticles(pagination.prevPageUrl)" class="page-link">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a href="#" class="page-link text-dark">Page {{pagination.currentPage}} of {{pagination.lastPage}}</a>
                </li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.nextPageUrl}]">
                    <a href="#" v-on:click="fetchArticles(pagination.nextPageUrl)" class="page-link">Next</a>
                </li>
            </ul>
        </nav>
        <div>
        <!-- <div v-for="article in articleGet" v-bind:key="article.id" class="card card-brand"> -->
            <table v-for="article in articleGet" v-bind:key="article.id" class="table table-responsive table-striped table-hover">
                <thead>
                    <tr>
                        <th>Article Title</th>
                        <th>Article Body</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{article.title}}</td>
                        <td>{{article.body}}</td>
                        <td>
                            <span v-on:click="deleteArticle(article.id)" class="btn btn-danger btn-sm">Del</span> | 
                            <span @click="editArticle(article)" class="btn btn-success btn-sm">Edit</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- <h3>{{article.title}}</h3>
            <p>{{article.body}}</p> -->
        </div>
    </div>
</template>



<script>
    export default {
        // mounted() {
        //     console.log('Component mounted.')
        // },
        data(){
            return{
                test: "This is article labels",
                articleGet: [],
                article: {
                    id: '',
                    title: '',
                    body: '',
                },
                article_id: '',
                pagination:{},
                edit: false
            }
        },
        created(){
            this.fetchArticles();
        },
        methods:{
            fetchArticles(url){
                let vm = this;
                url = url || 'api/article';
                // fetch('api/article')
                fetch(url)
                .then(result => result.json())
                .then(result => {
                    this.articleGet = result.data;
                    // console.log(result.links);
                    vm.makePagination(result.meta, result.links);
                })
                .catch(err => console.log(err));
            },
            makePagination(meta, links){
                let pagination = {
                    currentPage: meta.current_page,
                    lastPage: meta.last_page,
                    nextPageUrl: links.next,
                    prevPageUrl: links.prev
                }
                this.pagination = pagination;
            },
            deleteArticle(id){
                if(confirm('Are you sure you want to delete this article? ')){
                    fetch(`api/article/${id}`, {method: 'delete'})
                    .then(res => res.json())
                    .then(res => {
                        // console.log(res.data.title);
                        alert('Article '+ res.data.title+ ' deleted');
                        this.fetchArticles();
                    }).catch(err => console.log(err))
                }
            },
            editArticle(article){
                this.edit = true;
                this.article.id = article.id;
                this.article_id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;

                // fetch(`api/article`, {method: 'put'})
                // .then(res => res.json())
                // .then(res => {

                // }). catch(err => console.log(err))
            },
            addArticle(){
                if(this.edit ===false){
                    // add to api
                    fetch('api/article/new', {
                        method: 'post',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article Added');
                        this.fetchArticles();
                    })
                }
                else{
                    // update article
                    fetch('api/article', {
                        method: 'put',
                        body: JSON.stringify(this.article),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article Updated');
                        this.fetchArticles();
                    })
                    // .catch(err => console.log(err))
                }
            },
            
        }
    }
</script>