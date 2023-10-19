<template>
    <b-modal v-model="showModal" title="Financial Benefits Breakdown" size="xl" hide-footer header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered>    
        <b-form class="customform mb-2" v-if="selected">
            <div class="row">
                <div class="col-md-12">
                    <b-row v-if="!show" class="mb-4 mt-2">
                        <div class="responsive">
                            <table class="table table-centered table-bordered table-nowrap">
                                <thead class="thead-light align-middle text-center">
                                    <tr class="table-light fw-bold fs-13 text-primary">
                                        <td colspan="2">{{selected.month.toUpperCase() }} BATCH {{selected.batch}} <span class="fs-12 fw-medium text-muted">({{selected.created_at}})</span></td>
                                    </tr>
                                    <tr class="fw-bold font-size-11">
                                        <td>No. of Scholars</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center">
                                    <tr>
                                        <td width="50%">{{selected.lists.length}} </td>
                                        <td width="50%">₱ {{ formatMoney(selected.total)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-nowrap align-middle mb-0">
                                <thead class="table-dark">
                                    <tr class="fs-11">
                                        <th width="25%" class="text-center">Account No.</th>
                                        <th width="50%" class="text-center">Name</th>
                                        <th width="25%" class="text-center">Total</th>
                                    </tr>
                                </thead>
                            </table>
                            <SimpleBar class="align-items-center d-flex justify-content-center" :style="{ 'max-height': height + 'px' }">
                                <table class="table table-centered table-bordered table-nowrap mb-0">
                                    <tbody >
                                        <tr v-for="l in selected.lists" v-bind:key="l.id" style="cursor: pointer;" @click="view(l)">
                                            <td width="25%" class="text-center">{{l.account_no}}</td>
                                            <td width="50%" class="text-center">{{l.name}}</td>
                                            <td width="25%" class="text-center">₱ {{formatMoney(l.total)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </SimpleBar>
                        </div>
                    </b-row>
                    <b-row v-else class="mb-4 mt-2">
                        <div class="responsive">
                            <table class="table table-centered table-bordered table-nowrap">
                                <thead class="thead-light align-middle text-center">
                                    <tr class="fw-bold fs-12">
                                        <td>Name</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center fw-semibold text-primary fs-12">
                                    <tr>
                                        <td width="50%">{{breakdown.name}} </td>
                                        <td width="50%">₱ {{ formatMoney(breakdown.total)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-nowrap align-middle mb-0">
                                <thead class="table-dark">
                                    <tr class="fs-11">
                                        <th class="text-center" width="16%">Semester</th>
                                        <th v-for="(l,index) in headers" v-bind:key="index" :width="73/headers.length+'%'" class="text-center">{{l}}</th>
                                        <th class="text-center" width="11%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(e,index) in breakdown.enrollments" v-bind:key="index">
                                        <tr class="fs-11" v-if="(counts[index] != 0) ? true : false">
                                            <td class="text-center">{{e.academic_year}} - {{e.semester}}</td>
                                            <td v-for="(l,index) in headers" v-bind:key="index" class="text-center">
                                                ₱{{calculate(l,e)}}
                                            </td>
                                            <td class="text-center"> 
                                                ₱{{formatMoney(e.total)}}
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <thead>
                                    <tr class="table-light fs-11 fw-semibold">
                                        <td class="text-center">Total</td>
                                        <td v-for="(l,index) in headers" v-bind:key="index" class="text-center">
                                            ₱{{formatMoney(check(l))}}
                                        </td>
                                        <td class="text-center">₱ {{formatMoney(breakdown.total)}}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </b-row>
                    <button v-if="show" @click="back()" class="btn btn-light float-end" type="button">
                        <div class="btn-content"> Back </div>
                    </button>
                     <button v-else  @click="showModal = false" class="btn btn-light float-end" type="button">
                        <div class="btn-content"> Close </div>
                    </button>
                </div>
            </div>
        </b-form>
    </b-modal>
</template>

<script>
import { SimpleBar } from 'simplebar-vue3';
export default {
    components: { SimpleBar },
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: '',
            attachment: [],
            height: window.innerHeight - 557,
            breakdown: '',
            show: false,
            headers: [],
            semesters: [],
            totals: [],
            counts: []
        }
    },

    methods: {
        set(data) {
            this.headers = [];
            this.semesters = [];
            this.totals = [];
            this.show = false;
            this.selected = data;
            this.showModal = true;
            this.counts = [];
        },
        view(data){
            this.headers = [];
            this.totals = [];
            this.show = false;
            this.breakdown = data;
            this.counts = [];

            this.breakdown.enrollments.map((list) => {
                list.benefits.map((list) => {
                    if (!this.headers.includes(list.benefit.short)) {
                        this.headers.push(list.benefit.short);
                    }
                });
            });
            this.show = true;
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        calculate(data,benefits){
            if(data != 'Total'){
                var total = 0;
                benefits.benefits.map((list) => {
                    if(list.benefit.short == data) {
                        total = parseInt(total) + parseInt(list.amount);
                    }
                });
                this.totals.push(total);
                return this.formatMoney(total);
            }else{
                let t = this.formatMoney(this.totals.reduce((a, b) => a + b, 0));
                this.totals = [];
                return t;
            };
            return 0;
        },
        check(l){
            var total = 0;
            this.breakdown.enrollments.map((list) => {
                list.benefits.map((list) => {
                    if(list.benefit.short == l) {
                    total = parseInt(total) + parseInt(list.amount);
                }
                });
            });
            return total;
        },
        back(){
            this.show = false;
        }
    }
}
</script>
