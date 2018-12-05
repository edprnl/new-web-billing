<template>
    <div><!-- main container -->
        <notifications group="notification" />
        <div class="animated fadeIn"> <!-- main div -->
            <b-row> <!-- main row -->
                <b-col sm="12">
                    <b-card > <!-- main card -->
                        <h5 slot="header">  <!-- table header -->
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Location List
                                <small class="font-italic">List of all registered locations.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('location')">
                                            <i class="fa fa-plus-circle"></i> Create New Location
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.locations.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table  
                                    :filter="filters.locations.criteria"
                                    :fields="tables.locations.fields"
                                    :items.sync="tables.locations.items"
                                    :current-page="paginations.locations.currentPage"
                                    :per-page="paginations.locations.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn :size="'sm'" variant="danger" @click="onLocationDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.locations.totalRows" :per-page="paginations.locations.perPage" v-model="paginations.locations.currentPage"
                                     class="my-0" />
                                </b-col>
                        </b-row> <!-- Pagination -->
                        
                    </b-card><!-- main card -->
                </b-col>
            </b-row> <!-- main row -->

            </div><!-- main div -->

            <div> <!-- modal div -->
                <b-modal 
                    v-model="showModalEntry"
                    :noCloseOnEsc="true"
                    :noCloseOnBackdrop="true"
                >
                
                <div slot="modal-title"> <!-- modal title -->
                    Location Entry - {{entryMode}}
                </div> <!-- modal title -->

                <b-col lg=12> <!-- modal body -->
                    <b-form @keydown="resetFieldStates('location')">
                        <b-form-group>
                            <label for="location_code">* Location Code</label>
                            <b-form-input
                                id="location_code"
                                v-model="forms.location.fields.location_code"
                                :state="forms.location.states.location_code"
                                type="text"
                                placeholder="Location Code">
                            </b-form-input>
                            <b-form-invalid-feedback>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <span v-for="itemError in forms.location.errors.location_code">
                                    {{itemError}}
                                </span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group>
                            <label>* Location Desc</label>
                            <b-form-input
                                id="location_desc"
                                v-model="forms.location.fields.location_desc"
                                :state="forms.location.states.location_desc"
                                type="text"
                                placeholder="Location Description">
                            </b-form-input>
                            <b-form-invalid-feedback>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <span v-for="itemError in forms.location.errors.location_desc">
                                    {{itemError}}
                                </span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-form>
                </b-col> <!-- modal body -->

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-button :disabled="forms.location.isSaving" variant="primary" @click="onLocationEntry">
                        <icon v-if="forms.location.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        Save
                    </b-button>
                    <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>
                </div> <!-- modal footer buttons -->

                </b-modal>
            </div> <!-- modal div -->

</div> <!-- main container -->

   
</template>

<script>
export default {
    name: 'locations',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        forms:{
            location : {
                isSaving: false,
                fields: {
                    location_id: null,
                    location_code: null,
                    location_desc: null
                },
                states: {
                    location_code: null,
                    location_desc: null
                },
                errors: {
                    location_code: null,
                    location_desc: null
                }
            }
        },
        tables: {
          locations: {
                fields: [
                {
                    key: 'location_code',
                    label: 'Code',
                    thStyle: {width: '150px'}
                },
                {
                    key: 'location_desc',
                    label: 'Location Name'
                },
                {
                    
                    key: 'action',
                    label: '',
                    thStyle: {width: '75px'}
                },
                ],
                items: []
            }
        },
        filters: {
          locations: {
            criteria: null
          }
        },
        paginations: {
          locations: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
      }
    },
    methods:{
        clearFilter(){
            this.filters.locations.criteria = null;
            //alert("aa");
        },
        onLocationEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('location', true, 'locations')
            }
            else{
                this.updateEntity('location', 'location_id', true, 'locations')
            }
        
            //this.showModalEntry=false
            //this.fillTableList('categories');
        },
        onLocationDelete(data){
            this.deleteEntity('location', data.item.location_id, false, 'locations')
        },
        setUpdate(data){
            this.fillEntityForm('location', data.item.location_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {

    },
    created () {
      this.fillTableList('locations')
      //console.log(this.tables.locations.items);
    }
  }
</script>

