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
                                Department List
                                <small class="font-italic">List of all registered departments.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button v-if="checkRights('2-6')" variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('department')">
                                            <i class="fa fa-plus-circle"></i> Create New Department
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.departments.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                    
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table 
                                    v-if="checkAction"
                                    responsive
                                    :filter="filters.departments.criteria"
                                    :fields="tables.departments.fields"
                                    :items.sync="tables.departments.items"
                                    :current-page="paginations.departments.currentPage"
                                    :per-page="paginations.departments.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn v-if="checkRights('2-7')" :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn v-if="checkRights('2-8')" :size="'sm'" :disabled="forms.department.isDeleting" variant="danger" @click="setDelete(data)">
                                        <icon v-if="forms.department.isDeleting" name="sync" spin></icon>
                                        <i v-else class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.departments.totalRows" :per-page="paginations.departments.perPage" v-model="paginations.departments.currentPage"
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
                @shown="focusElement('department_code')"
            >
            
            <div slot="modal-title"> <!-- modal title -->
                Department Entry - {{entryMode}}
            </div> <!-- modal title -->

            <b-col lg=12> <!-- modal body -->
                <b-form @keydown="resetFieldStates('department')" autocomplete="off">
                    <b-form-group>
                        <label for="department_code">* Department Code</label>
                        <b-form-input
                            id="department_code"
                            v-model="forms.department.fields.department_code"
                            type="text"
                            placeholder="Department Code"
                            ref="department_code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Department Desc</label>
                        <b-form-input
                            ref="department_desc"
                            id="department_desc"
                            v-model="forms.department.fields.department_desc"
                            type="text"
                            placeholder="Department Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col> <!-- modal body -->

            <div slot="modal-footer"><!-- modal footer buttons -->
                <b-button :disabled="forms.department.isSaving" variant="primary" @click="onDepartmentEntry">
                    <icon v-if="forms.department.isSaving" name="sync" spin></icon>
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
                    Delete Department
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this department?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.department.isSaving" variant="primary" @click="onDepartmentDelete">
                        <icon v-if="forms.department.isSaving" name="sync" spin></icon>
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
    name: 'departments',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        forms:{
            department : {
                isSaving: false,
                isDeleting: false,
                fields: {
                    department_id: null,
                    department_code: null,
                    department_desc: null
                }
            }
        },
        tables: {
          departments: {
                fields: [
                {
                    key: 'department_code',
                    label: 'Code',
                    thStyle: {width: '150px'}
                },
                {
                    key: 'department_desc',
                    label: 'Department Name'
                },
                {   
                    key: 'action',
                    label: '',
                    thStyle: {width: '80px'},
                    tdClass: 'text-center'
                },
                ],
                items: []
            }
        },
        filters: {
          departments: {
            criteria: null
          }
        },
        paginations: {
          departments: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        department_id: null,
        row: []
      }
    },
    methods:{
        onDepartmentEntry () {
            if(this.entryMode == 'Add'){
                this.createEntity('department', true, 'departments')
            }
            else{
                this.updateEntity('department', 'department_id', true, this.row)
            }
        },
        onDepartmentDelete(){
            this.deleteEntity('department', this.department_id, true, 'departments', 'department_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('department', data.item.department_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.department_id = data.item.department_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.resetFieldStates('department')
            this.fillEntityForm('department', data.item.department_id)
            this.showModalEntry=true
            this.entryMode='Edit'
        }
    },
    computed: {
        checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('2-7') || this.checkRights('2-8')) == false){
                    this.tables.departments.fields.pop()
                }
            }
            return true
        }
    },
    created () {
      this.fillTableList('departments')
      //console.log(this.tables.departments.items);
    }
  }
</script>

