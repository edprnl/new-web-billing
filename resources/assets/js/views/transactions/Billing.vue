<template>
    <div>
        <b-modal 
            v-model="showModalPeriod"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Billing Period
            </div>
            
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Billing Period</label>
                        <select2
                            :allowClear="false"
                            :placeholder="'Select Billing Period'"
                            v-model="forms.period.fields.period_id"
                            @input="getPeriodInfo"
                        >
                            <option v-for="period in options.periods.items" :key="period.period_id" :value="period.period_id">{{ period.period_start_date | moment("MMMM DD, YYYY")}} - {{ period.period_end_date | moment("MMMM DD, YYYY")}}</option>
                        </select2>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Due Date</label>
                        <b-form-input 
                            v-model="forms.period.fields.period_due_date"
                            type="text" 
                            placeholder="Due Date"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col lg=6>
                    <b-form-group>
                        <label>App Year</label>
                        <b-form-input 
                            v-model="forms.period.fields.app_year"
                            type="text" 
                            placeholder="App Year"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg=6>
                    <b-form-group>
                        <label>Month</label>
                        <b-form-input 
                            v-model="forms.period.fields.month_name"
                            type="text" 
                            placeholder="Month"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Department</label>
                        <select2
                            :allowClear="false"
                            :placeholder="'Select Department'"
                            v-model="forms.period.fields.department_id"
                        >
                            <option v-for="department in options.departments.items" :key="department.department_id" :value="department.department_id">{{department.department_desc}}</option>
                        </select2>
                    </b-form-group>
                </b-col>
            </b-row>
            <div slot="modal-footer">
                <b-button variant="primary" @click="addCharges(charge_type)">
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalPeriod=false">Close</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'billing',
    data () {
        return {
            entryMode: 'Add',
            showEntry: false,
            showModalDelete: false,
            showModalCharges: false,
            showModalPeriod: true,
            options: {
                periods: {
                    items: []
                },
                departments: {
                    items: []
                },
            },
            forms: {
                period: {
                    fields: {
                        period_id: null,
                        period_due_date: null,
                        app_year: null,
                        month_id: null,
                        month_name: null,
                        department_id: null
                    }
                }
            }
        }
    },
    methods:{
        getPeriodInfo: function (value, data){
            if(data.length > 0){
                var period = this.options.periods.items[data[0].element.index]
                this.forms.period.fields.period_due_date = moment(period.period_due_date, 'YYYY-MM-DD').format("MMMM DD, YYYY")
                this.forms.period.fields.app_year = period.app_year
                this.forms.period.fields.month_name = period.month_name
                this.forms.period.fields.month_id = period.month_id
            }
            else{
                //this.contract.tenant_code = '';
            }
        }
    },
    created(){
        this.fillOptionsList('periods')
        this.fillOptionsList('departments')
    }
}
</script>