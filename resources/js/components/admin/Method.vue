<script>
import Multiselect from 'vue-multiselect'
export default {
    props: ['labid','testnameid','agencyid','sampletypeid'],
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            pagination: {},
            testmethods : [],
            methods: [],
            method: '',
            references: [],
            reference: '',
            fee: '',
            type: '',
            showmethods : false
        }
    },

    methods : { 

        asyncMethod(value){
            this.fetchMethodReference('Method',value);
        },

        asyncReference(value){
            this.fetchMethodReference('Reference',value);
        },

        fetchMethodReference(type,value) {
            axios.post(this.currentUrl + '/request/admin/methodreference/search', {
                word: value,
                type : type,
                laboratory_id: this.labid
            })
            .then(response => {
                (type == 'Method') ? this.methods = response.data.data : this.references = response.data.data;
            })
            .catch(err => console.log(err));
        },

        fetchTestMethod(id) {
            axios.get(this.currentUrl + '/request/admin/testmethods/'+id+'/'+this.agencyid+'/'+this.sampletypeid)
            .then(response => {
                this.testmethods = response.data.data;
            })
            .catch(err => console.log(err));
        },

        addMethod(method){
            this.addMethodReference(method,'Method');
        },

        addReference(reference){
            this.addMethodReference(reference,'Reference');
        },

        addMethodReference(name,type){
            axios.post(this.currentUrl + '/request/admin/methodreference/store', {
                laboratory_id: this.labid,
                name: name,
                type : type
            })
            .then(response => { 
                (type == 'Method') ? this.method = response.data.data : this.reference = response.data.data;
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

        emithis(){
            this.$emit('testids', this.method.id,this.reference.id, this.fee);
        },
        
        addmore(){
            this.testmethods = [];
        }
    },

    components: { Multiselect }
}
</script>


<template>
    <div class="col-xl-12">
        <hr class="mb-4">
        <div v-if="testmethods.length == 0">
            <multiselect  
                :taggable="true" 
                tag-placeholder="Add new Method" 
                @tag="addMethod" 
                v-model="method" 
                :options="methods" 
                placeholder="Select Method" 
                id="ajax" @search-change="asyncMethod"
                :searchable="true"
                label="name" track-by="id">
            </multiselect>

            <multiselect  
                v-if="method != ''"
                :taggable="true" 
                tag-placeholder="Add new Reference" 
                @tag="addReference" 
                v-model="reference" 
                :options="references" 
                placeholder="Select Reference" 
                id="ajax" @search-change="asyncReference"
                :searchable="true"
                label="name" track-by="id">
            </multiselect>

            <div class="form-group" v-if="reference != ''">
                <!-- <label for="formrow-firstname-input">Fee <span v-if="errors.fee" class="haveerror">({{ errors.fee[0] }})</span></label>  -->
                <input type="text" @keyup="emithis" class="form-control" placeholder="Fee" id="formrow-firstname-input" v-model="fee" style="text-transform: capitalize;">
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
</template>