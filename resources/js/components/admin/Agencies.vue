<script>
    import Multiselect from 'vue-multiselect'
    export default {
        data(){
            return {
                currentUrl: window.location.origin,
                errors: [],
                pagination: {},
                keyword: '',
                agencies: [],
                regions: [],
                provinces: [],
                municipalities: [],
                types: [],
                agency: {
                    id: '',
                    name: '',
                    acronym: '',
                    code: '',
                    website: '',
                    type: '',
                    region: '',
                    province: '',
                    municipality: '',
                    address: ''
                },
                editable : false,
                type: '',
                selected: 1,
                trselected : ''
            }
        },

        created(){
            this.fetchType();
            this.fetchRegion();
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
                page_url = page_url || this.currentUrl + '/request/admin/agencies/paginated/'+this.type;
                axios.get(page_url)
                .then(response => {
                    this.agencies = response.data.data;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

            fetchType() {
                axios.get(this.currentUrl + '/request/admin/dropdowns/'+'Agency Type')
                    .then(response => {
                        this.types = response.data.data;
                        this.type = this.types[0].id;
                        this.fetch();
                    })
                    .catch(err => console.log(err));
            },

            fetchRegion() {
                axios.get(this.currentUrl + '/request/regions')
                    .then(response => {
                        this.regions = response.data.data;
                    })
                    .catch(err => console.log(err));
            },

            fetchProvince($id) {
                axios.get(this.currentUrl + '/request/provinces/' + $id)
                    .then(response => {
                        this.provinces = response.data.data;
                    })
                    .catch(err => console.log(err));
            },

            fetchMunicipality($id) {
                axios.get(this.currentUrl + '/request/municipalities/' + $id)
                    .then(response => {
                        this.municipalities = response.data.data;
                    })
                    .catch(err => console.log(err));
            },

            onChangeRegion($id) {
                this.fetchProvince($id);
                this.agency.province = '';
                this.agency.municipality = '';
            },

            onChangeProvince($id) {
                this.fetchMunicipality($id);
                this.agency.municipality = '';
            },

            search(){
                let vm = this;
                axios.post(this.currentUrl + '/request/admin/agency/search',{
                    keyword: this.keyword,
                    type : this.type
                })
                .then(response => {
                    this.agencies = response.data.data;;
                    vm.makePagination(response.data.meta, response.data.links);
                })
                .catch(err => console.log(err));
            },

           
            create() {
                 this.errors = [];
                if (this.editable === false) {
                    axios.post(this.currentUrl + '/request/admin/agency/store', {
                        name: this.agency.name,
                        acronym: this.agency.acronym,
                        code: this.agency.code,
                        type: this.type,
                        region: this.agency.region.id,
                        province: this.agency.province.id,
                        municipality: this.agency.municipality.id,
                        address: this.agency.address,
                        website: this.agency.website
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
                    axios.put(this.currentUrl + '/request/admin/agency/store', {
                        id: this.agency.id,
                        name: this.agency.name,
                        acronym: this.agency.acronym,
                        code: this.agency.code,
                        type: this.type,
                        region: this.agency.region.id,
                        province: this.agency.province.id,
                        municipality: this.agency.municipality.id,
                        address: this.agency.address,
                        website: this.agency.website
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

            pick(number){
                this.selected = number;
                this.type = number;
                this.fetch();
            },

            view(agency) {
                this.editable = true;
                this.agency.id = agency.id;
                this.agency.name = agency.name;
                this.agency.acronym = agency.acronym;
                this.agency.code = agency.code;
                this.agency.website = agency.website;
                this.agency.address = agency.address;
                this.agency.region = agency.region;
                this.agency.province = agency.province;
                this.agency.municipality = agency.municipality;
                this.agency.type = agency.type;
                this.fetchProvince(agency.region.id);
                this.fetchMunicipality(agency.province.id);
                $("#new").modal('show');
                this.trselected = agency.id;
            },

            clear() {
                this.agency.id = "";
                this.agency.name = "";
                this.agency.acronym = "";
                this.agency.code = "";
                this.agency.website = "";
                this.agency.address = "";
                this.agency.region = "";
                this.agency.province = "";
                this.agency.municipality = "";
                this.agency.type = "";
                this.editable = false;
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
                                <div class="mb-3">
                                    <div class="dropdown">
                                        <button class="btn btn-danger btn-block" type="button" data-toggle="modal" data-target="#new">
                                            <i class="mdi mdi-plus mr-1"></i> Create New
                                        </button>
                                    </div>
                                </div>
                                <div class="mail-list mt-4">
                                    <a v-for="dd in types" v-bind:key="dd.id" @click="pick(dd.id)" :class="{active:selected == dd.id}"><i class="icofont icofont-dotted-right mr-2"></i> {{dd.code}}  </a>
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
                                        <!-- <multiselect 
                                        v-model="type" 
                                        @input="changetype(type)"
                                        :options="types"
                                        :allow-empty="false"
                                        deselect-label="Can't remove this value"
                                        label="name"
                                        >
                                        </multiselect> -->
                                    </div>
                                    <div class="col-xl-8 col-sm-6">
                                        <form class="float-sm-right form-inline">
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
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Acronym</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Created at</th>
                                            <!-- <th>Updated at</th> -->
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="agency in agencies" v-bind:key="agency.id" :class="{'table-success':trselected == agency.id}">
                                            <td>{{agency.name}}</td>
                                            <td class="text-center">{{agency.acronym}}</td>
                                            <td class="text-center">{{agency.type.code}}</td>
                                            <td class="text-center">{{agency.created_at}}</td>
                                            <!-- <td>{{agency.updated_at}}</td> -->
                                            <td class="text-center">
                                                <a class="mr-3 text-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><i class="icofont icofont-eye-alt font-size-13"></i></a>
                                                <a class="mr-3 text-warning" @click="view(agency)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icofont icofont-edit font-size-13"></i></a>
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
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <form  @submit.prevent="create">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New {{type.name}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="p-2 customerform">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Agency Name <span v-if="errors.name" class="haveerror">({{ errors.name[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="agency.name" style="text-transform: capitalize;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Acronym <span v-if="errors.acronym" class="haveerror">({{ errors.acronym[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="agency.acronym" style="text-transform: capitalize;">
                                                </div>
                                            </div>
                                             <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Code <span v-if="errors.code" class="haveerror">({{ errors.code[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="agency.code" style="text-transform: capitalize;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-group">
                                                    <label for="formrow-firstname-input">Website <span v-if="errors.website" class="haveerror">({{ errors.website[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="agency.website">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="formrow-email-input">Region <span v-if="errors.region" class="haveerror">({{ errors.region[0] }})</span></label>
                                                <multiselect @input="onChangeRegion(agency.region.id)" v-model="agency.region"
                                                    :options="regions" 
                                                    placeholder="Select Region"  
                                                    :allow-empty="false"
                                                    deselect-label="Can't remove this value" 
                                                    label="name" track-by="id">
                                                </multiselect>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="formrow-email-input">Province <span v-if="errors.province" class="haveerror">({{ errors.province[0] }})</span></label>
                                                <multiselect @input="onChangeProvince(agency.province.id)" v-model="agency.province"
                                                    :options="provinces" placeholder="Select Province" label="name" track-by="id">
                                                </multiselect>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="formrow-email-input">Municipality <span v-if="errors.municipality" class="haveerror">({{ errors.province[0] }})</span></label>
                                                <multiselect v-model="agency.municipality" :options="municipalities"
                                                    placeholder="Select Municipality" label="name" track-by="id">
                                                </multiselect>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="formrow-firstname-input">Address<span v-if="errors.address" class="haveerror">({{ errors.address[0] }})</span></label>
                                                    <input type="text" class="form-control" id="formrow-firstname-input" v-model="agency.address" style="text-transform: capitalize;">
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