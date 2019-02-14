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
                                Billing Period List
                                <small class="font-italic">List of all registered billing periods.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button v-if="checkRights('9-34')" variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('period')">
                                            <i class="fa fa-plus-circle"></i> Create New Billing Period
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.periods.criteria"
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
                                    :filter="filters.periods.criteria"
                                    :fields="tables.periods.fields"
                                    :items.sync="tables.periods.items"
                                    :current-page="paginations.periods.currentPage"
                                    :per-page="paginations.periods.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn v-if="checkRights('9-35')" :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn v-if="checkRights('9-36')" :size="'sm'" variant="danger" @click="setDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.periods.totalRows" :per-page="paginations.periods.perPage" v-model="paginations.periods.currentPage"
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
                @shown="focusElement('period_start_date')"
            >
                
                <div slot="modal-title"> <!-- modal title -->
                    Billing Period Entry - {{entryMode}}
                </div> <!-- modal title -->

                <b-col lg=12> <!-- modal body -->
                    <b-form @keydown="resetFieldStates('period')" autocomplete="off">
                        <b-form-group>
                            <label for="period_start_date">* Period Start Date</label>
                            <date-picker 
                                ref="period_start_date"
                                id="period_start_date"
                                v-model="forms.period.fields.period_start_date"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="MMMM DD, YYYY"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>

                        <b-form-group>
                            <label for="period_end_date">* Period End Date</label>
                            <date-picker 
                                ref="period_end_date"
                                id="period_end_date"
                                v-model="forms.period.fields.period_end_date"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="MMMM DD, YYYY"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>

                        <b-form-group>
                            <label>* Applicable Month </label>
                            <select2
                                ref="month_id"
                                :allowClear="false"
                                :placeholder="'Select Applicable Month'"
                                v-model="forms.period.fields.month_id"
                            >
                                <option v-for="month in options.months.items" :key="month.month_id" :value="month.month_id">{{month.month_name}}</option>
                            </select2>
                        </b-form-group>

                        <b-form-group>
                            <label for="app_year">* Applicable Year</label>
                            <date-picker 
                                ref="app_year"
                                id="app_year"
                                v-model="year"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="YYYY"
                                type="year"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>

                        <b-form-group>
                            <label for="period_due_date">* Period Due Date</label>
                            <date-picker 
                                id="period_due_date"
                                v-model="forms.period.fields.period_due_date"
                                lang="en" 
                                input-class="form-control mx-input"
                                format="MMMM DD, YYYY"
                                :clearable="false">
                            </date-picker>
                        </b-form-group>

                    </b-form>
                </b-col> <!-- modal body -->

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-button :disabled="forms.period.isSaving" variant="primary" @click="onPeriodEntry">
                        <icon v-if="forms.period.isSaving" name="sync" spin></icon>
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
                    Delete Billing Period
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this billing period?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.period.isSaving" variant="primary" @click="onPeriodDelete">
                        <icon v-if="forms.period.isSaving" name="sync" spin></icon>
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
    name: 'periods',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        options:{
            months: {
                items: []
            }
        },
        forms:{
            period : {
                isSaving: false,
                fields: {
                    period_id: null,
                    period_start_date: null,
                    period_end_date: null,
                    month_id: null,
                    app_year: null,
                    period_due_date: null
                }
            }
        },
        tables: {
          periods: {
                fields: [
                {
                    key: 'period_start_date',
                    label: 'Start Date',
                    formatter: (value) => {
                            return this.moment(value,'MMMM DD, Y')
                                
                        }
                },
                {
                    key: 'period_end_date',
                    label: 'End Date',
                    formatter: (value) => {
                            return this.moment(value,'MMMM DD, Y')
                                
                        }
                },
                {
                    key: 'month_name',
                    label: 'Applicable Month'
                },
                {
                    key: 'app_year',
                    label: 'Applicable Year'
                },
                {
                    key: 'period_due_date',
                    label: 'Due Date',
                    formatter: (value) => {
                            return this.moment(value,'MMMM DD, Y')
                                
                        }
                },
                {
                    
                    key: 'action',
                    label: '',
                    thStyle: {width: '75px'},
                    tdClass: 'text-center'
                },
                ],
                items: []
            }
        },
        filters: {
          periods: {
            criteria: null
          }
        },
        paginations: {
          periods: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        period_id: null,
        row: []
      }
    },
    methods:{
        onPeriodEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('period', true, 'periods')
            }
            else{
               this.updateEntity('period', 'period_id', true, this.row)
            }
        },
        onPeriodDelete(){
            this.deleteEntity('period', this.period_id, true, 'periods', 'period_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('period', data.item.period_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.period_id = data.item.period_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('period', data.item.period_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {
             year:{
                get: function(){
                    if(this.forms.period.fields.app_year != null && this.forms.period.fields.app_year != 0){
                        if(typeof this.forms.period.fields.app_year == 'number'){
                            console.log(this.forms.period.fields.app_year)
                            return moment(this.forms.period.fields.app_year + '-01' + '-01').format("MMMM DD, YYYY")
                        }
                        else{
                            return moment(this.forms.period.fields.app_year).format("MMMM DD, YYYY")
                        }
                    }
                    else{
                        this.forms.period.fields.app_year = null
                        return null
                    }
                   
                },
                set: function(newValue){
                    this.forms.period.fields.app_year = newValue
                }
            },
            checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('9-35') || this.checkRights('9-36')) == false){
                    this.tables.periods.fields.pop()
                }
            }
            return true
        }
    },
    created () {
      this.fillTableList('periods')
      this.fillOptionsList('months')
      //console.log(this.tables.locations.items);
    }
  }
</script>

