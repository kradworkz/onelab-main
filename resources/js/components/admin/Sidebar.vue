<script>
import Multiselect from 'vue-multiselect'
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            errors: [],
            pagination: {},
            types: [],
            type: '',
            agencies: [],
            agency: '',
            laboratories: [],
            laboratory: ''
        }
    },

    created(){
        this.fetchType();
    },

    methods : {
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
                this.$emit('ids', this.agency.id,this.laboratory.id);
            })
            .catch(err => console.log(err));
        },

        onChangeLaboratory(id) {
            this.$emit('ids', this.agency.id, id);
        },
    },

    components: { Multiselect }
}
</script>

<template>
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
    </div>
</template>