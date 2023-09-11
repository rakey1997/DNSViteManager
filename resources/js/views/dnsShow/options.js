// 定义一个生成选项的函数
function generateOptions(data, commonOptions) {
    const options = {};

    Object.keys(data).forEach((key) => {
        options[key] = [...commonOptions, ...data[key]];
    });

    return options;
}

// 公共选项
const commonOptions = [
    {
        label: "No",
        prop: "no",
        type: "index",
        width: "80",
        sortable: true,
    },
    {
        label: "dns_type",
        prop: "dns_type",
        sortable: true,
    },
    {
        label: "dns_role",
        prop: "dns_role",
        sortable: true,
    },
    {
        label: "server_location",
        prop: "server_location",
        sortable: true,
    },
    {
        label: "dns_operator",
        prop: "dns_operator_combine",
        sortable: true,
    },
    {
        label: "dns_operator_en",
        prop: "dns_operator_combine_en",
        sortable: true,
    },
];

// 数据选项
const specialOptions = {
    data_v4: [
        {
            label: "server_ip_public_v4",
            prop: "server_ip_public_v4",
            sortable: true,
        },
    ],
    data_v6: [
        {
            label: "server_ip_public_v6",
            prop: "server_ip_public_v6",
            sortable: true,
        },
    ],
    anydata_v4: [
        {
            label: "server_ip_anycast_v4",
            prop: "server_ip_anycast_v4",
            sortable: true,
        },
    ],
    anydata_v6: [
        {
            label: "server_ip_anycast_v6",
            prop: "server_ip_anycast_v6",
            sortable: true,
        },
    ],
};

// 生成 options
export const options = generateOptions(specialOptions, commonOptions);

// 生成 testOptions，并添加额外的 dns_result 字段
export const testOptions = generateOptions(specialOptions, [
    {
        label: "dns_result",
        prop: "dns_result",
        sortable: true,
    },
    {
        label: "Selection",
        prop: "selection",
        type: "selection",
        width: "60",
    },
    ...commonOptions,
]);

const wwwCommonOptions = [
    {
        label: "No",
        prop: "no",
        type: "index",
        width: "80",
        sortable: true,
    },
    {
        label: "status",
        prop: "status",
        sortable: true,
    },
    {
        label: "server_location",
        prop: "server_location",
        sortable: true,
    },
    {
        label: "dns_operator",
        prop: "dns_operator",
        sortable: true,
    },
    {
        label: "dns_operator_en",
        prop: "dns_operator_combine",
        sortable: true,
    },
    {
        label: "page_name",
        prop: "page_name",
        sortable: true,
    },
    {
        label: "page_name_ip",
        prop: "page_name_ip",
        sortable: true,
    },
    {
        label: "page_name_port",
        prop: "page_name_port",
        sortable: true,
    },
];

const wwwSpecialOptions = {
    data_v4: [
        {
            label: "server_ip_public_v4",
            prop: "server_ip_public_v4",
            sortable: true,
        },
    ],
    data_v6: [
        {
            label: "server_ip_public_v6",
            prop: "server_ip_public_v6",
            sortable: true,
        },
    ],
    anydata_v4: [
        {
            label: "server_ip_anycast_v4",
            prop: "server_ip_anycast_v4",
            sortable: true,
        },
    ],
    anydata_v6: [
        {
            label: "server_ip_anycast_v6",
            prop: "server_ip_anycast_v6",
            sortable: true,
        },
    ],
};

Object.keys(wwwSpecialOptions).forEach((key) => {
    wwwSpecialOptions[key].unshift(...wwwCommonOptions);
});

export const wwwOptions = wwwSpecialOptions;

export const soaSpecialOptions = [
    {
        label: "No",
        prop: "no",
        type: "index",
        width: "80",
        sortable: true,
    },
    {
        label: "dns_authname",
        prop: "dns_authname",
        sortable: true,
    },
    {
        label: "tld_server_name",
        prop: "tld_server_name",
        sortable: true,
    },
    {
        label: "tld_manager_email",
        prop: "tld_manager_email",
        sortable: true,
    },
];

export const debugInfoOptions = [
    {
        label: "No",
        prop: "id",
        width: "80",
        sortable: true,
    },
    {
        label: "category",
        prop: "category",
        sortable: true,
    },
    {
        label: "monitor_class",
        prop: "monitor_class",
        sortable: true,
    },
    {
        label: "monitor_type",
        prop: "monitor_type",
        sortable: true,
    },
    {
        label: "monitor_purpose",
        prop: "monitor_purpose",
        sortable: true,
    },
    {
        label: "monitor_method",
        prop: "monitor_method",
        sortable: true,
    },
    {
        label: "monitor_database",
        prop: "monitor_database",
        sortable: true,
    },
    {
        label: "monitor_log_path",
        prop: "monitor_log_path",
        sortable: true,
    },
    {
        label: "monitor_log_name",
        prop: "monitor_log_name",
        sortable: true,
    },
    {
        label: "monitor_msg_template",
        prop: "monitor_msg_template",
        sortable: true,
    },
    {
        label: "monitor_msg_example",
        prop: "monitor_msg_example",
        sortable: true,
    },
];
