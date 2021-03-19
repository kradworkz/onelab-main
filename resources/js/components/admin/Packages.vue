<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                keyword: '',
                testservices: [],
                packages: [],
                pckage: {
                    id: '',
                    name: '',
                    fee: '',
                    agency: '',
                    sampletype: '',
                    testservices: {}
                },
                selected : [],
                agency : '',
                laboratory: '',
                sampletypes: [],
                sampletype: '',
                testnames: [],
                testname: '',
                type: 'Sampletype',
                editable: false,
                trselected : ''
            }
        },

        created(){
            // this.fetchTest();
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

            fetch(page_url) {
                let vm = this;
                page_url = page_url || this.currentUrl + '/request/admin/packages/'+this.sampletype.id+'/'+this.agency;

                axios.get(page_url)
                .then(response => {
                    this.packages = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            fetchTest(page_url) {
                let vm = this;
                page_url = page_url || this.currentUrl + '/request/admin/testservices/'+this.sampletype.id+'/'+this.agency;

                axios.get(page_url)
                .then(response => {
                    this.testservices = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            create() {
                this.errors = [];
                if (this.editable === false) {
                    axios.post(this.currentUrl + '/request/admin/package/store', {
                        name: this.pckage.name,
                        fee: this.pckage.fee,
                        agency: this.agency,
                        sampletype: this.sampletype.id,
                        testservices: this.selected,
                    })
                    .then(response => {
                        this.fetch();
                        this.clear();
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
                    axios.put(this.currentUrl + '/request/admin/package/store', {
                        id: this.pckage.id,
                        name: this.pckage.name,
                        fee: this.pckage.fee,
                        agency: this.agency,
                        sampletype: this.sampletype.id,
                    })
                    .then(response => {
                        let page_url = '/request/admin/packages/'+this.sampletype.id+'/'+this.agency+'?page=' + this.pagination.current_page;
                        this.fetch(page_url);
                        this.clear();
                        $("#new").modal('hide');
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
            
            selectedids(agency,lab){
                this.agency = agency;
                this.laboratory = lab;
                this.asyncFind();
                this.fetchTest();
            },

            asyncFind(value) {
                if(value != ''){
                    axios.post(this.currentUrl + '/request/admin/sampletest/search', {
                        word: value,
                        lab_id: this.laboratory,
                        type: this.type
                    })
                    .then(response => {
                        this.sampletypes = response.data.data;
                        this.sampletype = this.sampletypes[0];
                        this.fetch();
                    })
                    .catch(err => console.log(err));
                }
            },

            view(pckage){
                this.editable = true;
                this.trselected = pckage.id;
                this.pckage.id = pckage.id;
                this.pckage.name = pckage.name;
                this.pckage.fee = pckage.fee;
                this.testservices = pckage.lists;
                $("#new").modal('show');
            },

            onChangeSample(){
                this.fetch();
            },

            addpackage(){
                this.clear();
                this.fetchTest();
            },

            clear(){    
                this.editable = false;
                this.pckage.name = '';
                this.pckage.fee = '';
                this.testservices = [];
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
                            <button type="button" class="btn  btn-primary btn-block waves-effect waves-light mb-4" @click="addpackage" data-toggle="modal" data-target="#new" :disabled="sampletype == ''">
                                <i class="icofont icofont-test-tube-alt font-size-16 align-middle mr-1"></i> Create Package
                            </button>
                            <div class="mb-4">
                               <sidebar @ids="selectedids"></sidebar>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="w-100">
                    <div class="card" >
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-xl-4 col-sm-4 form-inline">
                                    <multiselect  
                                        @input="onChangeSample()" 
                                        v-model="sampletype" 
                                        :options="sampletypes" 
                                            :allow-empty="false"
                                        :select-label="'Select'"
                                        deselect-label="Can't remove this value"
                                        tag-placeholder="Add new Sampletype" 
                                        placeholder="Select Sampletype" 
                                        id="ajax" @search-change="asyncFind"
                                        :searchable="true"
                                        label="name" track-by="id">
                                    </multiselect>
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

                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead>
                                         <tr>
                                            <th>Package Name</th>
                                            <th class="text-center">Fee</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Updated at</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pckage in packages" v-bind:key="pckage.id" :class="{'table-success':trselected == pckage.id}">
                                            <td>{{pckage.name}}</td>
                                            <td class="text-center">{{pckage.fee}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{pckage.created_at}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{pckage.updated_at}}</td>
                                            <td class="text-center">
                                                <a class="mr-3 text-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><i class="icofont icofont-eye-alt font-size-13"></i></a>
                                                <a class="mr-3 text-warning" @click="view(pckage)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icofont icofont-edit font-size-13"></i></a>
                                                <a class="text-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="icofont icofont-ui-delete font-size-13"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end mb-2">
                                    <li class="page-item"><a class="page-link" v-bind:class="[{disabled: !pagination.prev_page_url}]" @click="fetch(pagination.prev_page_url)">Previous</a></li>
                                    <li class="page-item"><a class="page-link" >Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                                    <li class="page-item"><a class="page-link" v-bind:class="[{disabled: !pagination.next_page_url}]" @click="fetch(pagination.next_page_url)">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                 </div>

                <div class="modal fade exampleModal" id="new" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <form @submit.prevent="create">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Package</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2 customerform">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Package Name <span v-if="errors.name" class="haveerror">({{ errors.name[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="pckage.name" style="text-transform: capitalize;">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Package Fee <span v-if="errors.fee" class="haveerror">({{ errors.fee[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="pckage.fee" style="text-transform: capitalize;">
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-centered table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th v-if="editable == false">#</th>
                                                    <th>Testname</th>
                                                    <th>Method</th>
                                                    <th>Reference</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="test in testservices" v-bind:key="'a-'+test.id">
                                                    <td v-if="editable == false">
                                                        <div class="custom-control custom-checkbox">
                                                            <input :id="'customCheck_' + test.id" type="checkbox" :value="test.id" v-model="selected" class="custom-control-input" />
                                                            <label class="custom-control-label" :for="'customCheck_' + test.id">&nbsp;</label>
                                                        </div>
                                                    </td>
                                                    <td>{{test.testname}}</td>
                                                    <td>{{test.method}}</td>
                                                    <td>{{test.reference}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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