<template>
        <el-form 
        class="header"
        status-icon
        :model="queryForm" 
        :rules="rules"
        label-position="top"
        label-width="100px"
    >
        <el-row :gutter="20">
            <el-col :span="8">
            <el-form-item :label="$t('dnsTest.prefix')" class="custom-form-item">
                <el-input clearable v-model="queryForm.prefix"></el-input>
            </el-form-item>
            </el-col>
        
            <el-col :span="2">
            <el-form-item :label="$t('dnsTest.testDomain')">
                <el-select v-model="queryForm.testDomain" placeholder="Test DNS Domain" size="small">
                <el-option v-for="item in domainOptions" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            </el-col>
            <el-col :span="2">
            <el-form-item :label="$t('dnsTest.testType')">
                <el-select v-model="queryForm.testType" placeholder="DNS Type" size="small">
                <el-option v-for="item in typeOptions" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            </el-col>
            
            <el-col :span="2" class="custom-button-col">
            <el-form-item>
                <el-button type="primary" icon="Files" @click="test_DNS">{{$t('dnsTest.testDNS')}}</el-button>
            </el-form-item>
            </el-col>
        </el-row>
    </el-form>
    
    <Table 
        v-for="(tableDataKey,index) in Object.keys(tableData)" 
        :key="index" 
        :tableData="tableData[tableDataKey]" 
        :options="testOptions[tableDataKey.replace('SpecialList', '').replace('White', '').toLowerCase()]"
        :tableTitle="tableTitle[index]"
        :dataSource="tableDataKey"
        :flags="tableData[tableDataKey].length!=0"
        ref="tables"
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
    import { getDNSInfos,testDNS } from "../../api/dnsInfo";
    import { testOptions } from "./options";
    import { useI18n } from "vue-i18n";
    import Table from "./components/table.vue";
    import testDialog from "./components/testDialog.vue";
    import {useStore} from "vuex";

    export default {
        name:'dnsMonitor',
        components:{Table,testDialog},
        setup(){
            const {t}=useI18n()
            const store=useStore()
            const dialogVisible=ref(false)
            const dialogTableValue=ref({})
            const tables = ref([]);
            const queryForm=reactive({
                filterNameList:['根服务器','权威服务器','递归服务器','缓存服务器'],
                testDomain:'.',
                prefix: "",
                testType:'SOA',
                IPV4Nodes:[],
                IPV4AnyNodes:[]
            })

            const values = ['SOA', 'NS', 'A', 'AAAA', 'CNAME'];
            const labels = ['SOA', 'NS', 'A Record', 'AAAA Record', 'CNAME'];

            const typeOptions = values.map((value, index) => ({
            value: value,
            label: labels[index],
            }));

            const domains = ['.', 'pcnl', "cec", "cecdl", "cityumo", "cmiot", "ctribj", "ctrigz", "ctrish",
            "gdcni", "gzhu", "hit", "hithrb", "hkct", "idtld", "iie", "mytld", "ncse", "pktld", "qianxin",
            "qihoobj", "rutld","sysu", "thtld", "ummo"
            ];

            const domainOptions = domains.map((value, index) => ({
            value: value,
            label: domains[index],
            }));

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
                const res=await getDNSInfos(queryForm)
                tableData.value=res.data
            }

            // 提取 selectRecords 中的值到对应的数组
            const getSelectRecords = (selectRecords) => {
                const resultArray = [];
                selectRecords.forEach(selectRecord => {
                resultArray.push(selectRecord);
                });
                return resultArray;
            };

            const processRecords = (records, resultMap) => {
                for (const item of records) {
                    const key = item.dns_role + item.dns_operator_combine_en;
                    if (resultMap.has(key)) {
                        item.dns_result = resultMap.get(key);
                    }
                }
            };

            const test_DNS = async () => {
                const IPV4Array = getSelectRecords(tables.value[0].selectRecords);
                const IPV4AnyArray = getSelectRecords(tables.value[1].selectRecords);

                queryForm.IPV4Nodes = IPV4Array;
                queryForm.IPV4AnyNodes = IPV4AnyArray;

                // 根据数组长度判断是否需要弹出确认框
                const needConfirm = IPV4Array.length !== 0 || IPV4AnyArray.length !== 0;

                const confirmMessage = needConfirm ? t('dnsTest.testBody') : t('dnsTest.noneSelect');

                try {
                    needConfirm?"":await ElMessageBox.confirm(confirmMessage, t('dnsTest.testTitle'), {
                        confirmButtonText: t('dialog.confirmButton'),
                        cancelButtonText: t('dialog.cancelButton'),
                        type: 'warning'
                    });

                    dialogVisible.value = true
                    dialogTableValue.value = {
                            opCode: true,
                            msg: t('dialog.inTest'),
                            result: t('dialog.waitResult')
                        };

                    const res = await testDNS(queryForm);
                    // tables.value[0].clearSelection()
                    // tables.value[1].clearSelection()

                    if (res.opCode) {
                        if(needConfirm){
                            const IPV4Map = new Map();
                            for (const item of res.IPV4) {
                                const key = item.dns_role + item.dns_operator_combine_en;
                                IPV4Map.set(key, item.dns_result);
                            }
                            processRecords(tables.value[0].selectRecords, IPV4Map);

                            const IPV4AnyMap = new Map();
                            for (const item of res.IPV4Any) {
                                const key = item.dns_role + item.dns_operator_combine_en;
                                IPV4AnyMap.set(key, item.dns_result);
                            }
                            processRecords(tables.value[1].selectRecords, IPV4AnyMap);
                        }else{
                            tableData.value = res.data;
                        }                      
                        dialogTableValue.value = {
                            opCode: true,
                            msg: t('dialog.doneTest'),
                            result: t('dialog.successTest')
                        };
                    } else {
                        dialogTableValue.value = {
                            opCode: false,
                            msg: t('dialog.doneTest'),
                            result: t('dialog.failTest')
                        };
                    }
                } catch {
                    ElMessage({
                        type: 'info',
                        message: t('dnsTest.cancelTest')
                    });
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

            initGetDNSInfosList()

            return{
                tableData,
                queryForm,
                tables,
                typeOptions,
                domainOptions,
                dialogVisible,
                dialogTableValue,
                initGetDNSInfosList,
                testOptions,
                tableTitle,
                test_DNS,
            }
        }
    }
</script>

<style lang="scss" scoped>
    .header {
    height: 50px; /* 设置合适的高度值 */
    }
    .custom-form-item .el-input {
    height: 26px; /* 设置合适的高度值 */
    }

    .custom-form-item .el-select,
    .custom-form-item .el-input {
    width: 100%; /* 设置合适的宽度值 */
    }
    .custom-button-col .el-form-item {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 100%;
    }
</style>