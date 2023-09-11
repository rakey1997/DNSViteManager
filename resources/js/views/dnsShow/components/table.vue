<template>
    <el-divider content-position="center" v-if="props.tableData.length">
        <div class="text">{{props.tableTitle}}</div>
    </el-divider>
    <el-form-item class="btn">
            <el-button type="primary" icon="UploadFilled" @click="toExcel" v-if="flags" >{{$t('dialog.exportBtn')}}</el-button>
        </el-form-item>
    <el-table ref="queryFormRef" :data="props.tableData" stripe fit style="width: 100%" 
        :default-sort="{prop:'no',order: 'ascending' }" 
        @selection-change="handleSelectionChange"
        v-if="props.tableData.length" 
        id="out-table" size="small">
        <el-table-column 
            v-for="(op,index) in props.options" 
            :key="index" 
            :prop="op.prop" 
            :type="op.type"
            :sortable="op.sortable"
            :selectable="()=>{if (dataSource=='Data_v4' ||dataSource=='AnyData_v4') return true}"
            :label="$t(`dnsTable.${op.label}`)"
            :width="op.width" 
        >
        <template v-slot="{row}" v-if="op.prop==='status'">
            <!-- <el-switch v-model="row.status" :active-value=true :inactive-value=false :disabled="true"/> -->
            <el-tag class="ml-2" type="info" v-if="row.status==null">未测试</el-tag>
            <el-tag class="ml-2" type="success" v-if="row.status=='1'">成功</el-tag>
            <el-tag class="ml-2" type="warning" v-if="row.status=='2'">失败</el-tag>
        </template>
        <template v-slot="{row}" v-else-if="op.prop==='dns_result'">
            <!-- <el-switch v-model="row.status" :active-value=true :inactive-value=false :disabled="true"/> -->
            <el-tag class="ml-2" type="info" v-if="row.dns_result ==null">未测试</el-tag>
            <el-tag class="ml-2" type="warning" v-else-if="row.dns_result.includes('timed out') || row.dns_result.includes('Error')">{{ row.dns_result }}</el-tag>
            <el-tag class="ml-2" type="success" v-else>{{ row.dns_result }}</el-tag>
        </template>
        </el-table-column>
    </el-table>
</template>

<script>
    import { reactive, ref } from '@vue/reactivity'
    import { saveAs } from 'file-saver';
    import * as XLSX from 'xlsx';
    export default {
        name:'Table',
        props:{
            options:{
                type:Object,
                default:()=>{},
            },
            tableData:{
                type:Object,
                default:()=>{}
            },
            tableTitle:{
                type:String,
            },
            dataSource:{
                type:String,
            },
            flags:{
                type:Boolean,
            },
        },
        setup(props){
            const queryFormRef=ref(null)
            const selectRecords=ref([])
            const handleSelectionChange=() =>{
                selectRecords.value = queryFormRef?.value?.getSelectionRows()||[] ;
            }
            const toExcel=()=>{
                /* 从表生成工作簿对象 */
                var wb = XLSX.utils.table_to_book(document.querySelector("#out-table"));
                /* 获取二进制字符串作为输出 */
                var wbout = XLSX.write(wb, {
                    bookType: "xlsx",
                    bookSST: true,
                    type: "array"
                });
                try {
                    saveAs(
                    //Blob 对象表示一个不可变、原始数据的类文件对象。
                    //Blob 表示的不一定是JavaScript原生格式的数据。
                    //File 接口基于Blob，继承了 blob 的功能并将其扩展使其支持用户系统上的文件。
                    //返回一个新创建的 Blob 对象，其内容由参数中给定的数组串联组成。
                    new Blob([wbout], { type: "application/octet-stream" }),
                    //设置导出文件名称
                    "export.xlsx"
                    );
                } catch (e) {
                    if (typeof console !== "undefined") console.log(e, wbout);
                }
                return wbout;
            }

            return{
                toExcel,
                queryFormRef,
                props,
                handleSelectionChange,
                selectRecords
            }
        },
        expose: ['selectRecords'],
    }
</script>

<style scoped>
    .text{
        font-size: medium;
        background-color:#FFFFFF;
        font-weight:bold;
    }
    .ml-2{
        white-space: normal;
        line-height: normal;
    }
</style>