<template>
    <makeFile :genFileData="fileData"/>
    <el-button type="primary" icon="Files" @click="testPage" >{{$t('table.testPage')}}</el-button>
    <Table 
        v-for="(tableDataKey,index) in Object.keys(tableData)" 
        :key="index" 
        :tableData="tableData[tableDataKey]" 
        :options="wwwOptions[tableDataKey.replace('SpecialList', '').replace('White', '').toLowerCase()]"
        :tableTitle="tableTitle[index]"
        :flags="tableData[tableDataKey].length!=0"
        >
    </Table>
    <testDialog v-model="dialogVisible" 
            :dialogTableValue="dialogTableValue"
            v-if="dialogVisible"
    >
    </testDialog>
</template>

<script>
import { reactive,ref } from '@vue/reactivity'
import { watch } from '@vue/runtime-core';
import { getWWWInfos,testWWWPage } from "../../api/dnsInfo";
import { wwwOptions } from "./options";
import { useI18n } from "vue-i18n";
import Table from "./components/table.vue";
import {useStore} from "vuex";
import makeFile from "./components/makeFile.vue";
import testDialog from "./components/testDialog.vue";

export default {
    name:'dnsWWW',
    components:{Table,makeFile,testDialog},
    setup(){
        const {t}=useI18n()
        const store=useStore()
        const dialogVisible=ref(false)
        const dialogTableValue=ref({})

        const tableData=ref([])
        let tableTitle_org=reactive({
            serverTitleipv4: "",
            AnycastServerTitleipv4: "",
            serverTitleipv6: "",
            AnycastServerTitleipv6: "",
            whiteServerTitleipv4: "",
            whiteAnycastServerTitleipv4: "",
            whiteServerTitleipv6: "",
            whiteAnycastServerTitleipv6:
                "",
        })
        let tableTitle=ref([]);

        const fileData={
                type:"www",
                fileNameList:{
                    fileName:"3w_page_conf.ini",
                    fileV6Name:"3w_page_conf_v6.ini",
                    whiteFileName:"whiteList_3w_page_conf.ini",
                    whiteV6FileName:"whiteList_3w_page_conf_v6.ini",
                }
            }

        const initGetWWWInfosList=async ()=>{
            const res=await getWWWInfos()
            tableData.value=res.data
        }

        const testPage=async ()=>{
            console.log("测试·");
            dialogVisible.value=true
            dialogTableValue.value={
                    opCode:true,
                    msg: t('dialog.inTest'),
                    result:t('dialog.waitResult'),
                }
            const res=await testWWWPage()
            if(res.opCode){
                tableData.value=res.data
                console.log(tableData.value);
                dialogTableValue.value={
                    opCode:true,
                    msg: t('dialog.doneTest'),
                    result:t('dialog.successTest'),
                }
            }else{
                dialogTableValue.value={
                    opCode:false,
                    msg: t('dialog.doneTest'),
                    result:t('dialog.failTest'),
                }
            }
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

        initGetWWWInfosList()

        return{
            store,
            tableData,
            dialogVisible,
            dialogTableValue,
            initGetWWWInfosList,
            wwwOptions,
            tableTitle,
            fileData,
            testPage
        }
    }
}
</script>