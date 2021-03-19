<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                keyword: '',
                types: [],
                type: '',
                agencies: [],
                agency: '',
                laboratories: [],
                laboratory: '',
                methods: [],
                method : '',
                references: [],
                reference: '',
                editable : false,
                fee :'',
            }
        },

        created(){
            this.fetchType();
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
                // page_url = page_url || this.currentUrl + '/request/admin/packages/'+this.sampletype.id+'/'+this.agency.id;

                // axios.get(page_url)
                // .then(response => {
                //     this.packages = response.data.data;;
                //     vm.makePagination(response.data.meta, response.data.links);
                // })
                // .catch(err => console.log(err));
            },
        
            fetchType() {
                axios.get(this.currentUrl + '/request/admin/dropdowns/'+'Agency Type')
                .then(response => {
                    this.types = response.data.data;
                    this.type = this.types[0];
                    this.fetchAgency(this.type.id); //TEMP
                })
                .catch(err => console.log(err));
            },

            onChangeType(id) {
                this.agency = '';
                this.fetchAgency(id);
            },

            fetchAgency(id) {
                axios.get(this.currentUrl + '/request/admin/agency/lists/'+id)
                .then(response => {
                    this.agencies = response.data.data;
                    this.agency = this.agencies[10]; //TEMP
                    this.fetchLaboratory(); //TEMP
                })
                .catch(err => console.log(err));
            },

            onChangeAgency(id) {
                this.fetchLaboratory();
            },

            fetchLaboratory(id) {
                axios.get(this.currentUrl + '/request/admin/dropdowns/Laboratory')
                .then(response => {
                    this.laboratories = response.data.data;
                    this.laboratory = this.laboratories[0]; //TEMP
                    this.fetchMethod(this.laboratory.id);
                    this.fetchReference(this.laboratory.id);
                    // this.fetch();
                })
                .catch(err => console.log(err));
            },

            onChangeLaboratory(id) {
                this.fetchMethod(id);
                this.fetchReference(id);
            },

            newtest(){
                $("#new").modal('show');
            },

            addOns(addon){
                this.addMethodReference(addon,'AddOns');
            },

            addOnsDefinition(addon){
                this.addMethodReference(addon,'AddOns Definition');
            },

            addMethodReference(name,type){
                axios.post(this.currentUrl + '/request/admin/methodreference/store', {
                    laboratory_id: this.laboratory.id,
                    name: name,
                    type : type
                })
                .then(response => { 
                 
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },

            fetchMethod(id) {
                axios.post(this.currentUrl + '/request/admin/methodreferences', {
                    laboratory_id: id,
                    type : 'AddOns'
                })
                .then(response => {
                    this.methods = response.data.data;
                })
                .catch(err => console.log(err));
            },

            fetchReference(id) {
                axios.post(this.currentUrl + '/request/admin/methodreferences', {
                    laboratory_id: id,
                    type : 'AddOns Definition'
                })
                .then(response => {
                    this.references = response.data.data;
                })
                .catch(err => console.log(err));
            },

            addTestservice(){
                axios.post(this.currentUrl + '/request/admin/testservice/store', {
                    agency: this.agency.id,
                    sampletype: 1,
                    testname: 1,
                    method : this.method.id,
                    reference: this.reference.id,
                    fee: this.fee
                })
                .then(response => { 
                    this.fetchTestMethod(this.testname.id);
                    this.fetch();
                    $("#new").modal('hide');
                    this.method = '';
                    this.reference = '';
                    this.fee = '';
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
            
            search(){

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
                                
                                <div class="mail-list">
                                    <multiselect 
                                        class="mb-1"
                                        v-model="type" 
                                        @input="onChangeType(type.id)"
                                        :options="types"
                                        :allow-empty="false"
                                        :select-label="'Select'"
                                        deselect-label="Can't remove this value"
                                        label="code"
                                        track-by="id"
                                        >
                                    </multiselect>
                                    <multiselect 
                                        class="mb-1"
                                        v-model="agency" 
                                        @input="onChangeAgency(agency)"
                                        :options="agencies"
                                        :allow-empty="false"
                                        :select-label="'Select'"
                                        deselect-label="Can't remove this value"
                                        label="acronym"
                                        track-by="id"
                                        >
                                    </multiselect>
                                    <multiselect 
                                        class="mb-1"
                                        v-model="laboratory" 
                                        @input="onChangeLaboratory(laboratory.id)"
                                        :options="laboratories"
                                        :allow-empty="false"
                                        :select-label="'Select'"
                                        deselect-label="Can't remove this value"
                                        label="code"
                                        track-by="id"
                                        >
                                    </multiselect>
                                    <!-- <a v-for="dd in types" v-bind:key="dd.id" @click="pick(dd.id)" :class="{active:selected == dd.id}"><i class="icofont icofont-dotted-right mr-2"></i> {{dd.code}}  </a> -->
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
                                    <div class="col-xl-4 col-sm-4 form-inline">
                                        
                                    </div>
                                   
                                    <div class="col-xl-8 col-sm-6">
                                        <form class="float-sm-right form-inline">
                                            <div class="mb-2 mr-2 mt-2">
                                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" @click="newtest">
                                                    <i class="icofont icofont-test-tube-alt font-size-16 align-middle mr-1"></i> Create Addons
                                                </button>
                                            </div>
                                            <div class="search-box">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control bg-light border-light rounded" placeholder="Search..." v-model="keyword" @keyup="search">
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
                                            <th>Addon Name</th>
                                            <th class="text-center">Code</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Updated at</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <!-- <tr v-for="addon in addonslist" v-bind:key="'a-'+addon.id">
                                            <td>{{addon.name}}</td>
                                            <td class="text-center">{{addone.fee}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{addon.created_at}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{addon.updated_at}}</td>
                                            <td class="text-center"><label class="label label-inverse-primary" @click="view(addon)">VIEW</label></td>
                                        </tr> -->
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
                            <form  @submit.prevent="addTestservice">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Package</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                   <div class="p-2 customerform">
                                        <div class="row">
                                            <multiselect  
                                                :taggable="true" 
                                                v-model="method" 
                                                :options="methods" 
                                                @tag="addOns" 
                                                tag-placeholder="Add Addon" 
                                                placeholder="Select Addone" 
                                                label="name" track-by="id">
                                            </multiselect>

                                            <multiselect  
                                                :taggable="true" 
                                                v-model="reference" 
                                                :options="references" 
                                                @tag="addOnsDefinition" 
                                                tag-placeholder="Add definition" 
                                                placeholder="Select Definition" 
                                                label="name" track-by="id">
                                            </multiselect>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <!-- <label for="formrow-firstname-input">Fee <span v-if="errors.fee" class="haveerror">({{ errors.fee[0] }})</span></label> -->
                                                    <input type="text" class="form-control" placeholder="Fee" id="formrow-firstname-input" v-model="fee" style="text-transform: capitalize;">
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