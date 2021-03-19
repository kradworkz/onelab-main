<script>
import VueUploadMultipleImage from 'vue-upload-multiple-image'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                articles : [],
                images :[],
                keyword: '',
                selected: 1,
                type: 'news',
                trselected: '',
                id: '',
                title: '',
                content: '',
                photos: [],
                editable : false
            }
        },

        created(){
            this.fetch();
        },

        methods : {
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },

            fetch(page_url){
                let vm = this;
                page_url = page_url || this.currentUrl + '/request/articlepage/'+this.type;
                axios.get(page_url)
                .then(response => {
                    this.articles = response.data.data;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            create(){
                this.errors = [];
                if (this.editable === false) {
                    axios.post(this.currentUrl + '/request/article/store', {
                        title: this.title,
                        content: this.content,
                        photos: this.photos,
                        type: this.type
                    })
                    .then(response => {
                        this.fetch();
                        $("#new").modal('hide');
                        this.clear();
                        Vue.$toast.success('<strong>Successfully Created</strong>', {
                            position: 'bottom-right'
                        });
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                } else {
                    axios.put(this.currentUrl + '/request/article/store', {
                        id: this.id,
                        title: this.title,
                        content: this.content,
                        photos: this.photos,
                        type: this.type
                    })
                    .then(response => {
                        this.createView = false;
                        let page_url = '/request/admin/agencies/paginated/'+this.type+'?page=' + this.pagination.current_page;
                        this.fetch(page_url);
                        $("#new").modal('hide');
                        this.clear();
                        Vue.$toast.success('<strong>Successfully Updated</strong>', {
                            position: 'bottom-right'
                        });
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                }
            },

            change(number){
                this.selected = number;
                if(number == 1){
                    this.type = 'news';
                }else{
                   this.type = 'announcements'
                }
            },

            clear(){
                this.errors = [];
                this.title = '';
                this.content = '';
                this.photos = '';
            },
            uploadImageSuccess(formData, index, fileList) {
                this.photos = fileList;
            },

            beforeRemove (index, done, fileList) {
                var r = confirm("remove image")
                if (r == true) {
                    done()
                }
            },

            editImage (formData, index, fileList) {
                //console.log('edit data', formData, index, fileList)
            }
        },
        components: {  VueUploadMultipleImage }
    }
</script>

<template>
    <div class="d-xl-flex">
        <div class="w-100">
            <div class="d-md-flex">

                <div class="card filemanager-sidebar mr-md-2">
                    <div class="card-body">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4">
                                <div class="mb-3">
                                    <div class="dropdown">
                                        <button class="btn btn-danger btn-block" type="button" data-toggle="modal" data-target="#new">
                                            <i class="mdi mdi-plus mr-1"></i> Create New
                                        </button>
                                    </div>
                                </div>
                                <div class="mail-list mt-4">
                                    <a @click="change(1)" :class="{active:selected == 1}">
                                        <i class="mdi mdi-format-list-bulleted mr-2"></i> 
                                        News
                                    </a>
                                    <a @click="change(2)" :class="{active:selected == 2}">
                                        <i class="mdi mdi-account-details mr-2"></i>Announcements
                                    </a>
                                   
                                </div>
                                <!-- <hr class="mb-4"> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="card">
                        <div class="card-body">

                            <div>
                                <div class="row mb-3">
                                    <div class="col-xl-4 col-sm-6 form-inline">
                                      
                                    </div>
                                    <div class="col-xl-8 col-sm-6">
                                        <form class="float-sm-right form-inline">
                                            <div class="search-box">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control bg-light border-light rounded" placeholder="Search..." v-model="keyword">
                                                    <i class="icofont icofont-search search-icon"></i>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="article in articles" v-bind:key="article.id"  :class="{'table-success':trselected == article.id}">
                                            <td>{{article.title}}</td>
                                            <td>{{article.author}}</td>
                                            <td>{{article.created_at}}</td>
                                            <td>{{article.updated_at}}</td>
                                            <td>
                                                <a class="mr-3 text-primary" @click="edit(article)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icofont icofont-edit font-size-13"></i></a>
                                                <a class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="icofont icofont-ui-delete font-size-13"></i></a>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end mb-2">
                                    <li class="page-item"><a class="page-link" v-bind:class="[{disabled: !pagination.prev_page_url}]" @click="fetch(pagination.prev_page_url)" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                    <li class="page-item"><a class="page-link" v-bind:class="[{disabled: !pagination.next_page_url}]" @click="fetch(pagination.next_page_url)" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="modal fade exampleModal" id="new" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <form  @submit.prevent="create">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New {{type}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Title <span v-if="errors.title" class="haveerror">({{ errors.title[0] }})</span></label>
                                                    <input type="text" v-model="title" class="form-control" style=" text-transform:capitalize;"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <textarea v-model="content" class="form-control" maxlength="225" rows="8" placeholder="This textarea has a limit of 225 chars."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <vue-upload-multiple-image
                                                    @upload-success="uploadImageSuccess"
                                                    @before-remove="beforeRemove"
                                                    @edit-image="editImage"
                                                    :data-images="images"
                                                    dragText = "Click/Drag Image"
                                                    browseText = "(Browse Image)"
                                                    primaryText = "Default Image"
                                                    markIsPrimaryText = "Make Primary"
                                                    popupText = "This is the default Image"
                                                    >
                                                </vue-upload-multiple-image>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

</template>