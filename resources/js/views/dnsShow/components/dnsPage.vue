<template>
        <Table 
            v-for="(tableDataKey,index) in Object.keys(tableData)" 
            :key="index" 
            :tableData="tableData[tableDataKey]" 
            :options="options[tableDataKey.replace('SpecialList', '').replace('White', '').toLowerCase()]"
            :tableTitle="tableTitle[index]"
            :flags="tableData[tableDataKey].length!=0"
            >
        </Table>
</template>

<script>
    import { reactive,ref } from '@vue/reactivity'
    import { watch } from '@vue/runtime-core';
    import { getDNSInfos } from "../../../api/dnsInfo";
    import { options } from "../options";
    import { useI18n } from "vue-i18n";
    import Table from "./table.vue";
    import {useStore} from "vuex";

    export default {
        name:'DNSPage',
        components:{Table},
        props:{
            queryForm:{
                type:Object,
                default:()=>{},
            },
        },
        setup(props){
            const {t}=useI18n()
            const store=useStore()

            const tableData=ref([])
            let tableTitle_org=reactive({
                serverTitleipv4: "",
                AnycastServerTitleipv4: "",
                serverTitleipv6: "",
                AnycastServerTitleipv6: "",
                specialServerTitleipv4: "",
                specialAnycastServerTitleipv4: "",
                specialServerTitleipv6: "",
                specialAnycastserverTitleipv6:
                    "",
                whiteServerTitleipv4: "",
                whiteAnycastServerTitleipv4: "",
                whiteServerTitleipv6: "",
                whiteAnycastServerTitleipv6:
                    "",
            })
            let tableTitle=ref([]);
            
            const initGetDNSInfosList=async ()=>{
                const res=await getDNSInfos(props.queryForm)
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

            initGetDNSInfosList()

            return{
                tableData,
                initGetDNSInfosList,
                options,
                tableTitle,
                props
            }
        }
    }
</script>

<style>

</style>