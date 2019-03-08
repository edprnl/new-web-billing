<template>
    <div>
        <notifications group="notification" />
        <b-modal
            v-model="showModalPeriod"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
            @shown="focusElement('period_id')"
        >
            <div slot="modal-title">
                Billing Period
            </div>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Billing Period</label>
                        <select2
                            ref="period_id"
                            :allowClear="false"
                            :placeholder="'Select Billing Period'"
                            v-model="forms.period.fields.period_id"
                            @input="getPeriodInfo"
                        >
                            <option v-for="period in options.periods.items" :key="period.period_id" :value="period.period_id">{{ moment(period.period_start_date, "MMMM DD, YYYY")}} - {{ moment(period.period_end_date, "MMMM DD, YYYY")}}</option>
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
            <div slot="modal-footer">
                <b-button variant="primary" @click="onInsertAr()">
                    <i class="fa fa-check"></i>
                    Send to Accounting
                </b-button>
                <b-button variant="secondary" @click="showModalPeriod=false">Close</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'ar_billing',
    data () {
        return {
            showModalPeriod: true,
            forms: {
                period: {
                    fields: {
                        period_id: null,
                        period_due_date: null,
                        app_year: null,
                        month_id: null,
                        month_name: null
                    }
                }
            },
            options: {
                periods: {
                    items: []
                },
            },
        }
    },
    methods:{
        async onInsertAr () {
            await this.$http.get('api/arbilling/getjournalinfo/' + this.forms.period.fields.period_id, {
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                this.$http.post('api/arbilling/insertjournalinfo', {info: response.data, period_id: this.forms.period.fields.period_id}, {
                    headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                })
                .then((response) => {
                    
                })
                .catch(error => {
                    if (!error.response) return
                    console.log(error)
                })
                response.data.forEach(info => {
                    
                })
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        getPeriodInfo: function (value, data){
            if(data.length > 0){
                var period = this.options.periods.items[data[0].element.index]
                this.forms.period.fields.period_due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
                this.forms.period.fields.app_year = period.app_year
                this.forms.period.fields.month_name = period.month_name
                this.forms.period.fields.month_id = period.month_id
                this.forms.period.fields.due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
            }
        },
    },
    created(){
        this.fillOptionsList('periods')
    },
}
</script>