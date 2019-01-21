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
                                Nature Of Business List
                                <small class="font-italic">List of all registered nature of business.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('natureofbusiness')">
                                            <i class="fa fa-plus-circle"></i> Create New Nature Of Business
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.natureofbusinesses.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table  
                                    :filter="filters.natureofbusinesses.criteria"
                                    :fields="tables.natureofbusinesses.fields"
                                    :items.sync="tables.natureofbusinesses.items"
                                    :current-page="paginations.natureofbusinesses.currentPage"
                                    :per-page="paginations.natureofbusinesses.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.natureofbusinesses.totalRows" :per-page="paginations.natureofbusinesses.perPage" v-model="paginations.natureofbusinesses.currentPage"
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
                @shown="focusElement('nature_of_business_code')"
            >
                
                <div slot="modal-title"> <!-- modal title -->
                    Nature Of Business Entry - {{entryMode}}
                </div> <!-- modal title -->

                <b-col lg=12> <!-- modal body -->
                    <b-form @keydown="resetFieldStates('natureofbusiness')" autocomplete="off">
                        <b-form-group>
                            <label for="nature_of_business_code">* Nature Of Business Code</label>
                            <b-form-input
                                ref="nature_of_business_code"
                                id="nature_of_business_code"
                                v-model="forms.natureofbusiness.fields.nature_of_business_code"
                                type="text"
                                placeholder="Nature Of Business Code">
                            </b-form-input>
                        </b-form-group>
                        <b-form-group>
                            <label>* Nature Of Business Desc</label>
                            <b-form-input
                                ref="nature_of_business_desc"
                                id="nature_of_business_desc"
                                v-model="forms.natureofbusiness.fields.nature_of_business_desc"
                                type="text"
                                placeholder="Nature Of Business Description">
                            </b-form-input>
                        </b-form-group>
                    </b-form>
                </b-col> <!-- modal body -->

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-button :disabled="forms.natureofbusiness.isSaving" variant="primary" @click="onNatureOfBusinessEntry">
                        <icon v-if="forms.natureofbusiness.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        Save
                    </b-button>
                    <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>
                </div> <!-- modal footer buttons -->

            </b-modal>
            <b-modal 
                v-model="showModalDelete"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
            >
                <div slot="modal-title">
                    Delete Nature of Business
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this nature of business?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.natureofbusiness.isSaving" variant="primary" @click="onNatureOfBusinessDelete">
                        <icon v-if="forms.natureofbusiness.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        OK
                    </b-button>
                    <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
                </div>
            </b-modal>
        </div> <!-- modal div -->

    </div> <!-- main container -->

   
</template>

<script>
export default {
    name: 'natureofbusiness',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        forms:{
            natureofbusiness : {
                isSaving: false,
                fields: {
                    nature_of_business_id: null,
                    nature_of_business_code: null,
                    nature_of_business_desc: null
                }
            }
        },
        tables: {
          natureofbusinesses: {
                fields: [
                {
                    key: 'nature_of_business_code',
                    label: 'Code',
                    thStyle: {width: '150px'}
                },
                {
                    key: 'nature_of_business_desc',
                    label: 'Description'
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
          natureofbusinesses: {
            criteria: null
          }
        },
        paginations: {
          natureofbusinesses: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        nature_of_business_id: null,
        row: []
      }
    },
    methods:{
        onNatureOfBusinessEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('natureofbusiness', true, 'natureofbusinesses')
            }
            else{
                this.updateEntity('natureofbusiness', 'nature_of_business_id', true, this.row)
            }
        },
        onNatureOfBusinessDelete(){
            this.deleteEntity('natureofbusiness', this.nature_of_business_id, true, 'natureofbusinesses', 'nature_of_business_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('natureofbusiness', data.item.nature_of_business_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.nature_of_business_id = data.item.nature_of_business_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('natureofbusiness', data.item.nature_of_business_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {

    },
    created () {
      this.fillTableList('natureofbusinesses')
      //console.log(this.tables.natureofbusiness.items);
    }
  }
</script>

