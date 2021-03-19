<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                keyword: '',
                dropdowns: [],
                dropdown: {
                    id: '',
                    name: '',
                    code: '',
                    percentage: '',
                    status: '',
                },
                lists : [],
                request: ["Agency Type","Request Type","Request From","Laboratory","Purpose"],
                customer: ["Bussiness Nature","Industry Type","Customer Type","Classification"],
                finance: ["Collection Type","Deposit Type","OR Category"],
                billing: ["Mode of Release","Discount","Payment Mode"],
                options: ["Agency Type","Request Type","Request From","Laboratory","Purpose"],
                editable : false,
                type: 'Agency Type',
                selected: 1,
                trselected: ''
            }
        },

        created(){
            this.fetch();
            this.add();
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
                page_url = page_url || this.currentUrl + '/request/admin/dropdownlist/'+this.type;
                axios.get(page_url)
                .then(response => {
                    this.dropdowns = response.data.data;
                     vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            search(){
                let vm = this;
                axios.post(this.currentUrl + '/request/admin/dropdownlist/search',{
                    keyword: this.keyword,
                    type : this.type
                })
                .then(response => {
                    this.dropdowns = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            change(number){
                this.selected = number;
                if(number == 1){
                    this.options = this.request;
                    this.type = this.request[0];
                    this.lists = [];
                    this.fetch();
                    this.add();
                }else if(number == 2){
                    this.options = this.customer;
                    this.type = this.customer[0];
                    this.lists = [];
                    this.fetch();
                     this.add();
                }else if(number == 3){
                    this.options = this.finance;
                    this.type = this.finance[0];
                    this.lists = [];
                    this.fetch();
                     this.add();
                }else{
                    this.options = this.billing;
                    this.type = this.billing[0];
                    this.lists = [];
                    this.fetch();
                     this.add();
                }
            },

            status(){

            },

            create() {
                this.errors = [];

                if (this.editable === false) {
                    axios.post(this.currentUrl + '/request/admin/dropdownlist/store',  {
                        type: this.type,
                        lists: this.lists,
                    })
                    .then(response => {
                        this.lists = [];
                        this.fetch();
                        $("#new").modal('hide');
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

                    const form = new FormData();
                    form.append('id', this.dropdown.id);
                    form.append('name', this.dropdown.name);
                    form.append('update', 'update');

                    (this.type == "Agency Type" || this.type == "Laboratory") ? form.append('code', this.dropdown.code) : '';
                    (this.type == "Discount") ? form.append('percentage', this.dropdown.percentage) : '';

                    axios.post(this.currentUrl + '/request/admin/dropdownlist/store', form)
                    .then(response => {
                        $('#new').modal('hide');
                        let page_url = '/request/admin/dropdownlist/'+this.type+'?page=' + this.pagination.current_page;
                        this.fetch(page_url);
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

            add() {
                if(this.type == "Agency Type" || this.type== "Laboratory"){
                    this.lists.push({code: ''})
                }else if(this.type == "Discount"){
                    this.lists.push({percentage: ''})
                }else{
                    this.lists.push({name: ''})
                }
            },

            del(index) {
                this.lists.splice(index, 1);
            },

            changetype(type){
                this.lists = [];
                this.type = type;
                this.fetch();
                this.add();
            },

            edit(dropdownlist){
                this.editable = true;
                this.dropdown.id = dropdownlist.id;
                this.dropdown.name = dropdownlist.name;
                this.dropdown.code = dropdownlist.code;
                this.dropdown.percentage = dropdownlist.percentage;
                $("#new").modal('show');
                this.trselected = dropdownlist.id;
            }
        
        },
        components: { Multiselect }
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
                                        Request 
                                    </a>
                                    <a @click="change(2)" :class="{active:selected == 2}">
                                        <i class="mdi mdi-account-details mr-2"></i>Customer
                                    </a>
                                    <a @click="change(3)" :class="{active:selected == 3}">
                                        <i class="mdi mdi-cash-register mr-2"></i>Finance
                                    </a>
                                    <a @click="change(4)" :class="{active:selected == 4}">
                                        <i class="mdi mdi-cash-multiple mr-2"></i>Billing
                                    </a>
                                </div>
                                <hr class="mb-4">
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
                                        <multiselect 
                                        v-model="type" 
                                        @input="changetype(type)"
                                        :options="options"
                                        :allow-empty="false"
                                        deselect-label="Can't remove this value"
                                        >
                                        </multiselect>
                                    </div>
                                    <div class="col-xl-8 col-sm-6">
                                        <form class="float-sm-right form-inline">
                                            <div class="search-box">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control bg-light border-light rounded" placeholder="Search..." v-model="keyword" @keyup="fetch">
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
                                            <th>Name</th>
                                            <th v-if="type == 'Agency Type' || type == 'Laboratory'">Code</th>
                                            <th v-if="type == 'Discount'">Percentage</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="dropdownlist in dropdowns" v-bind:key="dropdownlist.id"  :class="{'table-success':trselected == dropdownlist.id}">
                                            <td>{{dropdownlist.name}}</td>
                                            <td v-if="dropdownlist.type == 'Agency Type' || dropdownlist.type == 'Laboratory'">{{dropdownlist.code}}</td>
                                            <td v-if="dropdownlist.type == 'Discount'">{{dropdownlist.percentage}}%</td>
                                            <!-- <td><span v-html="dropdownlist.status"></span></td> -->
                                            <td>
                                                <span v-if="dropdownlist.status == 0" class="badge badge-lg badge-success">Active</span>
                                                <span v-else class="badge badge-danger">Inactive</span>
                                            </td>
                                            <td>{{dropdownlist.created_at}}</td>
                                            <td>{{dropdownlist.updated_at}}</td>
                                            <td>
                                                <a class="mr-3 text-primary" @click="edit(dropdownlist)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icofont icofont-edit font-size-13"></i></a>
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
                            <form  @submit.prevent="create" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New {{type}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2">
                                        <div class="inner-repeater mb-4" v-if="editable == false">
                                            <div class="inner form-group customerform">
                                                <div v-for="(list , index) in lists" v-bind:key="'a-'+list.id+index" class="inner mb-3 row">
                                                    <div class="col-md-7">
                                                        <input v-model="list.name" type="text" class="inner form-control" placeholder="Name"/>
                                                    </div>
                                                    <div class="col-md-2" v-if="type == 'Agency Type' || type== 'Laboratory' || type=='OR Category'">
                                                        <input v-model="list.code" type="text" class="inner form-control" placeholder="Code"/>
                                                    </div>
                                                    <div class="col-md-2" v-if="type == 'Discount'">
                                                        <input v-model="list.percentage" type="text" class="inner form-control" placeholder="Percentage"/>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <input type="button" class="btn btn-primary btn-block inner" value="x" @click="del(index)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <input  type="button" class="btn btn-success inner"  value="Add Number" @click="add"/>
                                        </div>
                                        <div v-else>
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group customerform">
                                                        <span v-if="errors.name" style="color: red; font-size: 12px;">{{ errors.name[0] }}</span>
                                                        <input type="text" v-model="dropdown.name" class="form-control" style="margin-bottom: 3px; text-transform:capitalize; padding: 15px 20px 15px 15px;"> 
                                                    </div>
                                                </div>
                                                <div class="col-md-3" v-if="type == 'Agency Type' || type== 'Laboratory'">
                                                    <div class="form-group customerform">
                                                        <span v-if="errors.code" style="color: red; font-size: 12px;"> {{ errors.code[0] }}</span>
                                                        <input type="text" v-model="dropdown.code" class="form-control" placeholder="Acronym/Code" style="margin-bottom: 3px; text-transform:capitalize; padding: 15px 20px 15px 15px;"> 
                                                    </div>
                                                </div>
                                                <div class="col-md-3" v-if="type == 'Discount'">
                                                    <div class="form-group customerform">
                                                        <span v-if="errors.percentage" style="color: red; font-size: 12px;"> {{ errors.percentage[0] }}</span>
                                                        <input type="text" v-model="dropdown.percentage" class="form-control" placeholder="Percentage" style="margin-bottom: 3px; text-transform:capitalize; padding: 15px 20px 15px 15px;"> 
                                                    </div>
                                                </div>
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>