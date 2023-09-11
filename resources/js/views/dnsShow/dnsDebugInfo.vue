<template>
    <el-table :data="tableData" stripe fit height="100%" style="width: 100%" >
        <el-table-column 
            v-for="(op,index) in debugInfoOptions" 
            :key="index" 
            :prop="op.prop" 
            :type="op.type"
            :label="tableTitle[index]"
            :width="op.width" 
        >
        </el-table-column>
    </el-table>
</template>

<script>
    import { ref,reactive } from '@vue/reactivity'
    import { watch } from '@vue/runtime-core';
    import { getDebugInfos } from "../../api/dnsInfo";
    import { debugInfoOptions } from "./options";
    import { useI18n } from "vue-i18n";
    import {useStore} from "vuex";
    
    export default {
        name:'dnsDebugINFO',
        setup(){
            const tableData=ref([])
            const {t}=useI18n()
            const store=useStore()

            let tableTitle_org=reactive({
                No: "",
                category: "",
                monitor_class: "",
                monitor_type: "",
                monitor_purpose: "",
                monitor_method: "",
                monitor_database: "",
                monitor_log_path: "",
                monitor_log_name: "",
                monitor_msg_template: "",
                monitor_msg_example: "",
            })

            let tableTitle=ref([]);

            const initGetDebugInfosList=async ()=>{
                const res=await getDebugInfos()
                tableData.value=res.data
            }

            initGetDebugInfosList()

            watch(
                () => store.getters.lang,
                () => {
                    Object.keys(tableTitle_org).forEach(key => {
                        tableTitle_org[key]=t(`debugInfoTable.${key}`)
                    });
                    tableTitle.value=Object.values(tableTitle_org)
                },
                { deep: true,immediate:true }
            );

            return{
                tableData,
                tableTitle,
                debugInfoOptions
            }
        }
    }
</script>