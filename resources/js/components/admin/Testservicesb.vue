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
                sampletypes: [],
                sampletype: '',
                editable : false,
                showsample : false,
                testnames: [],
                testname: '',
                showtest: false,
                methods: [],
                method: '',
                references : [],
                reference : '',
                fee :'',
                showmethod: false,
                testmethods : [],
                testservices: []
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
                page_url = page_url || this.currentUrl + '/request/admin/testservices/'+this.sampletype.id;

                axios.get(page_url)
                .then(response => {
                    this.testservices = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
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
                    this.fetchSampletype(this.laboratory.id);
                })
                .catch(err => console.log(err));
            },

            onChangeLaboratory(id) {
              
                this.showsample = true;
            },

            fetchSampletype(id) {
                axios.get(this.currentUrl + '/request/admin/sampletypes/' + id)
                .then(response => {
                    this.sampletypes = response.data.data;
                    this.testnames = [];
                    this.testname = '';
                    this.sampletype = this.sampletypes[1];
                    this.fetch();
                    this.fetchTestname(this.laboratory.id);
                })
                .catch(err => console.log(err));
            },

            asyncFind(value) {
                if(value != ''){
                    axios.post(this.currentUrl + '/request/admin/sampletype/search', {
                        word: value,
                        lab_id: this.laboratory.id
                    })
                    .then(response => {
                        this.sampletypes = response.data.data;
                    })
                    .catch(err => console.log(err));
                }
            },

            onChangeSample() {
                this.fetchTestname(this.laboratory.id);
                this.fetch();
                this.testnames = [];
                this.testname = '';
                this.showtest = true;
            },

            fetchTestname($id) {
                axios.get(this.currentUrl + '/request/admin/testnames/' + $id)
                .then(response => {
                    this.testnames = response.data.data;
                })
                .catch(err => console.log(err));
            },

            onChangeTest($id) {
                this.showmethod = true;
                this.fetchTestMethod($id);
            },

            addSampletype(sampletype){
                this.addSampleTest(sampletype,'Sampletype');
            },

            addTestname(testname){
                this.addSampleTest(testname,'Testname');
            },

            addSampleTest(name,type){
                axios.post(this.currentUrl + '/request/admin/sampletest/store', {
                    laboratory_id: this.laboratory.id,
                    name: name,
                    type: type
                })
                .then(response => { 
                    if(type == 'Testname'){
                        this.fetchTestname(this.laboratory.id);
                        this.testname = response.data.data;
                        this.fetchTestMethod(this.testname.id);
                        this.showmethod = true;
                    }else{
                        this.sampletype = response.data.data;
                        this.showtest = true;
                        this.testname = '';
                    }  
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },

            addMethod(method){
                this.addMethodReference(method,'Method');
            },

            addReference(reference){
                this.addMethodReference(reference,'Reference');
            },

            addMethodReference(name,type){
                axios.post(this.currentUrl + '/request/admin/methodreference/store', {
                    laboratory_id: this.laboratory.id,
                    name: name,
                    type : type
                })
                .then(response => { 
                    if(type == 'Method'){
                        this.fetchMethod(this.laboratory.id);
                        this.method = response.data.data;
                    }else{
                        this.fetchReference(this.laboratory.id);
                        this.reference = response.data.data;
                    }
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
                    type : 'Method'
                })
                .then(response => {
                    this.methods = response.data.data;
                })
                .catch(err => console.log(err));
            },

            fetchReference(id) {
                axios.post(this.currentUrl + '/request/admin/methodreferences', {
                    laboratory_id: id,
                    type : 'Reference'
                })
                .then(response => {
                    this.references = response.data.data;
                })
                .catch(err => console.log(err));
            },

            fetchTestMethod($id) {
                axios.get(this.currentUrl + '/request/admin/testmethods/'+$id+'/'+this.agency.id+'/'+this.sampletype.id)
                .then(response => {
                    this.testmethods = response.data.data;
                    if(this.testmethods.length < 1){
                        this.fetchMethod(this.laboratory.id);
                        this.fetchReference(this.laboratory.id);
                    }
                })
                .catch(err => console.log(err));
            },


            addTestservice(){
                axios.post(this.currentUrl + '/request/admin/testservice/store', {
                    agency: this.agency.id,
                    sampletype: this.sampletype.id,
                    testname: this.testname.id,
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

            newMethod(){
                $("#testmethod").modal('show');
                this.fetchMethod(this.laboratory.id);
                this.fetchReference(this.laboratory.id);
            },

            search(){

            },

            newtest(){
                $("#new").modal('show');
            },

            addmore(){
                this.testmethods = [];
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
                    <div class="card" >
                        <div class="card-body">

                            <div>
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
                                            <div class="mb-2 mr-2 mt-2">
                                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" @click="newtest" :disabled="sampletype == ''">
                                                    <i class="icofont icofont-test-tube-alt font-size-16 align-middle mr-1"></i> Create Testservice
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
                                            <th>Testname</th>
                                            <th class="text-center">Method</th>
                                            <th class="text-center">Reference</th>
                                            <th class="text-center">Fee</th>
                                            <th class="text-center">Created at</th>
                                            <th class="text-center">Updated at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="tests in testservices" v-bind:key="tests.id">
                                            <td>{{tests.testname}}</td>
                                            <td class="text-center">{{tests.method}}</td>
                                            <td class="text-center">{{tests.reference}}</td>
                                            <td class="text-center">{{tests.fee}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{tests.created_at}}</td>
                                            <td class="text-center" style="font-size: 12px;">{{tests.updated_at}}</td>
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
                            <form  @submit.prevent="addTestservice">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Testservices</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2 customerform">
                                         <multiselect 
                                            :taggable="true" 
                                            @input="onChangeTest(testname.id)"
                                            v-model="testname" 
                                            :options="testnames" 
                                            @tag="addTestname" 
                                            tag-placeholder="Add new Testsample" 
                                            placeholder="Select Testname" 
                                            label="name" track-by="id">
                                        </multiselect>
                                    </div>

                                    <div class="p-2 customerform" v-if="showmethod == true">
                                    <!-- <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-9">
                                            
                                        </div>
                                        <div class="col-md-3">
                                            <button style="margin-left: -10px;" type="button" @click="newMethod" class="btn btn-sm btn-info btn-round">Create Method</button>
                                        </div>
                                    </div> -->
                                    <div v-if="testmethods.length == 0">
                                        <!-- <div class="alert alert-default">
                                            <code> No method fot this test.</code>
                                            <button style="float:right;" type="button" @click="newMethod" class="btn btn-sm btn-info btn-round">Create Method</button>
                                        </div> -->
                                         <multiselect  
                                            :taggable="true" 
                                            v-model="method" 
                                            :options="methods" 
                                            @tag="addMethod" 
                                            tag-placeholder="Add new Method" 
                                            placeholder="Select Method" 
                                            label="name" track-by="id">
                                        </multiselect>

                                        <multiselect  
                                            :taggable="true" 
                                            v-model="reference" 
                                            :options="references" 
                                            @tag="addReference" 
                                            tag-placeholder="Add new Reference" 
                                            placeholder="Select Reference" 
                                            label="name" track-by="id">
                                        </multiselect>

                                        <div class="form-group">
                                            <!-- <label for="formrow-firstname-input">Fee <span v-if="errors.fee" class="haveerror">({{ errors.fee[0] }})</span></label> -->
                                            <input type="text" class="form-control" placeholder="Fee" id="formrow-firstname-input" v-model="fee" style="text-transform: capitalize;">
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div class="table-responsive">
                                            <table class="table project-list-table table-nowrap table-centered table-borderless">
                                                <tbody>
                                                    <tr v-for="tm in testmethods" v-bind:key="tm.id" class="table-success">
                                                        <td>
                                                            <h5 class="text-truncate font-size-14"><a href="#" class="text-dark">{{tm.method}}</a></h5>
                                                            <p class="text-muted mb-0">{{ tm.reference }}</p>
                                                        </td>
                                                        <td class="text-right">{{ tm.fee}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-primary waves-effect waves-light" @click="addmore">
                                                <i class="icofont icofont-test-tube-alt font-size-16 align-middle mr-1"></i> Add more
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" v-if="testmethods.length == 0">Save</button>
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