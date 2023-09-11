<template>
    <makeFile :genFileData="fileData"/>
    <Table 
        v-for="(tableDataKey,index) in Object.keys(tableData)" 
        :key="index" 
        :tableData="tableData[tableDataKey]" 
        :options="soaSpecialOptions"
        :tableTitle="tableTitle[index]"
        >
    </Table>
</template>

<script>
import { reactive,ref } from '@vue/reactivity'
import { watch } from '@vue/runtime-core';
import { getSOAInfos } from "../../api/dnsInfo";
import { soaSpecialOptions } from "./options";
import { useI18n } from "vue-i18n";
import Table from "./components/table.vue";
import {useStore} from "vuex";
import makeFile from "./components/makeFile.vue";

export default {
    name:'dnsSOA',
    components:{Table,makeFile},
    setup(){
        const {t}=useI18n()
        const store=useStore()

        const tableData=ref([])
        let tableTitle_org=reactive({
            authSOATitle: "",
            whiteAuthSOATitle: "",
        })
        let tableTitle=ref([]);

        const fileData={
                type:"soa",
                fileNameList:{
                    fileName:"soa_recursive.ini",
                    whiteFileName:"whiteList_soa_recursive.ini",
                }
            }

        const initGetSOAInfosList=async ()=>{
            const res=await getSOAInfos()
            tableData.value=res.data
        }

        watch(
            () => store.getters.lang,
            () => {
                Object.keys(tableTitle_org).forEach(key => {
                    tableTitle_org[key]=t(`dnsTableTitle.${key}`)
                });
                tableTitle.value=Object.values(tableTitle_org)
            },
            { deep: true,immediate:true }
        );

        initGetSOAInfosList()

        return{
            tableData,
            initGetSOAInfosList,
            soaSpecialOptions,
            tableTitle,
            fileData
        }
    }
}
</script>

<style>

</style>