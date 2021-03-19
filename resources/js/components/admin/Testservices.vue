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
                sampletypes: [],
                sampletype: '',
                testnames: [],
                testname: '',
                name: '',
                type: 'Sampletype',
                showsample: false,
                newtype: false,
                agency : '',
                laboratory: '',
                method: '',
                reference: '',
                fee: ''
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
                page_url = page_url || this.currentUrl + '/request/admin/testservices/'+this.sampletype.id+'/'+this.agency;

                axios.get(page_url)
                .then(response => {
                    this.testservices = response.data.data;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            create(){
                if(this.newtype == false){
                    axios.post(this.currentUrl + '/request/admin/testservice/store', {
                        agency: this.agency,
                        sampletype: this.sampletype.id,
                        testname: this.testname.id,
                        method : this.method,
                        reference: this.reference,
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
                }else{
                    (this.type == 'Sampletype') ? this.type = 'Sampletype' : this.type = 'Testname';

                    axios.post(this.currentUrl + '/request/admin/sampletest/store', {
                        laboratory_id: this.laboratory,
                        name: this.name,
                        type: this.type
                    })
                    .then(response => { 
                        if(this.type == 'Sampletype') {
                            $("#new").modal('hide');
                            this.sampletype = response.data.data;
                            this.fetch();
                        }else{
                            this.testname = response.data.data;
                            // this.$refs.getmethods.fetchTestMethod(this.testname);
                        }
                        this.clear();
                        Vue.$toast.success('<strong>Successfully Created</strong>', {
                            position: 'bottom-right'
                        }); 
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                            Vue.$toast.error('<strong>Theres something wrong.</strong>', {
                                position: 'bottom-right'
                            });
                        }
                    });
                }
            },
            
            selectedids(agency,lab){
                this.agency = agency;
                this.laboratory = lab;
                this.fetch();
                this.asyncFind();
            },

            asyncFind(value) {
                if(value != ''){
                    axios.post(this.currentUrl + '/request/admin/sampletest/search', {
                        word: value,
                        lab_id: this.laboratory,
                        type: this.type
                    })
                    .then(response => {
                        if(this.type == 'Sampletype'){
                            this.sampletypes = response.data.data;
                            this.sampletype = this.sampletypes[0];
                            this.fetch();
                        }else{
                            this.testnames = response.data.data;
                            
                            if(this.testnames.length == 0){

                            }else{
                                this.testname = this.testnames[0];
                                this.$refs.getmethods.fetchTestMethod(this.testname);
                            }
                                
                            $("#new").modal('show');
                        }
                        this.fetch();
                    })
                    .catch(err => console.log(err));
                }
            },

            onChangeSample(){
                this.fetch();
            },

            onChangeTest(id) {
                this.$refs.getmethods.fetchTestMethod(id);
            },

            newsampletest(type){
                this.newtype = true;
                this.type = type;
                $("#new").modal('show');
            },

            clear(){
                this.name = ''; 
                this.errors = [];
                this.newtype = false;
            },

            opentest(){
                this.name = ''; 
                this.errors = [];
                this.type = 'Testname';
                this.asyncFind();
            },

            selectedtestids(method,reference,fee){
                this.method = method;
                this.reference = reference;
                this.fee = fee;
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
                                <button type="button" class="btn btn-primary btn-block waves-effect waves-light mb-4" @click="opentest" :disabled="sampletype == ''">
                                    <i class="icofont icofont-test-tube-alt font-size-16 align-middle"></i> Create Testservice
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
                                <div class="col-xl-4 col-sm-4">
                                    <multiselect  
                                        @input="onChangeSample()" 
                                        v-model="sampletype" 
                                        :options="sampletypes" 
                                        :allow-empty="false"
                                        :select-label="'Select'"
                                        deselect-label="Can't remove this value"
                                        placeholder="Select Sampletype" 
                                        id="ajax" @search-change="asyncFind"
                                        :searchable="true"
                                        label="name" track-by="id">
                                    </multiselect>
                                </div>
                                <div class="col-xl-1 col-sm-1" style="margin-left: -20px;">
                                    <button type="button" class="btn btn-primary waves-effect waves-light" @click="newsampletest('Sampletype')">
                                        <i class="icofont font-size-16 align-middle icofont-plus-circle"></i>
                                    </button>
                                </div>
                                
                                <div class="col-xl-7 col-sm-7">
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
                                            <th>Testname</th>
                                            <th class="text-center">Method</th>
                                            <th class="text-center">Reference</th>
                                            <th class="text-center">Fee</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="tests in testservices" v-bind:key="tests.id">
                                            <td>{{tests.testname}}</td>
                                            <td class="text-center">{{tests.method}}</td>
                                            <td class="text-center">{{tests.reference}}</td>
                                            <td class="text-center">{{tests.fee}}</td>
                                            <td class="text-center">{{tests.created_at}}</td>
                                            <td class="text-center">
                                                <a class="mr-3 text-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><i class="icofont icofont-eye-alt font-size-13"></i></a>
                                                <a class="mr-3 text-warning" @click="view(tests)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icofont icofont-edit font-size-13"></i></a>
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
                            
                            <form @submit.prevent="create">
                                <div v-if="newtype == false">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">New Testservices</h5>
                                    </div>

                                    <div class="modal-body">
                                        <div class="p-2 customerform">
                                            <div class="row mb-3">
                                                <div class="col-xl-11 col-sm-11">
                                                    <multiselect 
                                                        @input="onChangeTest(testname.id)"
                                                        v-model="testname" 
                                                        :options="testnames"  
                                                        id="ajax" @search-change="asyncFind"
                                                        :allow-empty="false"
                                                        :select-label="'Select'"
                                                        deselect-label="Can't remove this value"
                                                        placeholder="Select Testname" 
                                                        label="name" track-by="id">
                                                    </multiselect>
                                                </div>
                                                <div class="col-xl-1 col-sm-1" style="margin-left: -20px;">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" @click="newsampletest('Testname')">
                                                        <i class="icofont font-size-16 align-middle icofont-plus-circle"></i>
                                                    </button>
                                                </div>
                                                <methodreference :labid="laboratory" :testnameid="testname.id" :agencyid="agency" :sampletypeid="sampletype.id" @testids="selectedtestids" ref="getmethods"></methodreference>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="modal-header">
                                        <h6 class="modal-title" id="exampleModalLabel">New {{this.type}}</h6>
                                    </div>
                                    <div class="p-2 customerform">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="formrow-firstname-input">{{this.type}} name <span v-if="errors.name" class="haveerror">({{ errors.name[0] }})</span></label> 
                                                <input type="text" class="form-control" :placeholder="'Enter '+type+'name'" id="formrow-firstname-input" v-model="name" style="text-transform: capitalize;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="clear">Close</button>
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