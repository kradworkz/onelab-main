<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                keyword: '',
                addons : [],
                methods: [],
                method : '',
                references: [],
                reference: '',
                selected : [],
                fee: '',
                agency : '',
                laboratory: '',
                editable: false,
                trselected : ''
            }
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
                page_url = page_url || this.currentUrl + '/request/admin/addons/'+this.laboratory+'/'+this.agency;

                axios.get(page_url)
                .then(response => {
                    this.addons = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            selectedids(agency,lab){
                this.agency = agency;
                this.laboratory = lab;
                this.fetchMethodReference('AddOns','');
                this.fetchMethodReference('AddOns Definition','');
                this.fetch();
            },

            addOns(addon){
                this.addMethodReference(addon,'AddOns');
            },

            addOnsDefinition(addon){
                this.addMethodReference(addon,'AddOns Definition');
            },

            addMethodReference(name,type){
                axios.post(this.currentUrl + '/request/admin/methodreference/store', {
                    laboratory_id: this.laboratory,
                    name: name,
                    type : type
                })
                .then(response => { 
                    (type == 'AddOns') ? this.method = response.data.data : this.reference = response.data.data;
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },

            asyncMethod(value){
                this.fetchMethodReference('AddOns',value);
            },

            asyncReference(value){
                this.fetchMethodReference('AddOns Definition',value);
            },

           
            fetchMethodReference(type,value) {
                axios.post(this.currentUrl + '/request/admin/methodreference/search', {
                    word: value,
                    type : type,
                    laboratory_id: this.laboratory
                })
                .then(response => {
                    (type == 'AddOns') ? this.methods = response.data.data : this.references = response.data.data;
                })
                .catch(err => console.log(err));
            },

            addTestservice(){
                axios.post(this.currentUrl + '/request/admin/testservice/store', {
                    agency: this.agency,
                    sampletype: 1,
                    testname: 1,
                    method : this.method.id,
                    reference: this.reference.id,
                    fee: this.fee
                })
                .then(response => { 
                    $("#new").modal('hide');
                    this.method = '';
                    this.reference = '';
                    this.fee = '';
                    this.fetch();
                    Vue.$toast.success('<strong>Successfully Created</strong>', {
                        position: 'bottom-right'
                    });
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },

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
                                <button type="button" class="btn btn-block btn-primary waves-effect waves-light mb-4" data-toggle="modal" data-target="#new">
                                    <i class="icofont icofont-test-tube-alt font-size-16 align-middle mr-1"></i> Create AddOn
                                </button>
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
                                            <th>Name</th>
                                            <th class="text-center">Definition</th>
                                            <th class="text-center">Fee</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pckage in addons" v-bind:key="pckage.id" :class="{'table-success':trselected == pckage.id}">
                                            <td>{{pckage.method}}</td>
                                            <td>{{pckage.reference}}</td>
                                            <td class="text-center">{{pckage.fee}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{pckage.created_at}}</td>
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
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form @submit.prevent="addTestservice">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Addon</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2 customerform">
                                        <div class="col-xl-12">
                                            <multiselect  
                                                :taggable="true" 
                                                v-model="method" 
                                                :options="methods" 
                                                @tag="addOns" 
                                                 id="ajax" @search-change="asyncMethod"
                                                tag-placeholder="Add Addon" 
                                                placeholder="Select Addone" 
                                                label="name" track-by="id">
                                            </multiselect>

                                            <multiselect  
                                                :taggable="true" 
                                                v-model="reference" 
                                                :options="references" 
                                                @tag="addOnsDefinition" 
                                                 id="ajax" @search-change="asyncReference"
                                                tag-placeholder="Add definition" 
                                                placeholder="Select Definition" 
                                                label="name" track-by="id">
                                            </multiselect>

                                            <div class="form-group">
                                                <!-- <label for="formrow-firstname-input">Fee <span v-if="errors.fee" class="haveerror">({{ errors.fee[0] }})</span></label> -->
                                                <input type="text" class="form-control" placeholder="Fee" id="formrow-firstname-input" v-model="fee" style="text-transform: capitalize;">
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