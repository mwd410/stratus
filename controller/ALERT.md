Alert API
=========

The Alert API provides the single source of access for the application to all
alert functionality. This includes basic CRUD operations for your alerts, but
also for querying for any triggered alerts.

Overview
-------------

<table>
    <tr>
        <td>URL</td>
        <td>Description</td>
    </tr>
    <tr>
        <td>/alert/info</td>
        <td>Provides static mappings of result properties and error types
            relevant only to the Alert API.
        </td>
    </tr>
    <tr>
        <td>/alert/index</td>
        <td>Returns all alerts for the logged in user.</td>
    </tr>
    <tr>
        <td>/alert/description</td>
        <td>Returns the description for an alert with specified parameters</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

/alert/info
-----------

**Parameters**

There are no parameter options.

**Result**



/alert/index
------------

**Parameters**

There are no parameter options.

**Result**

If success is false, `data` will be null, and `errors` will not be empty.

    {
        "success" : true,


The `data` property contains result data. This will be an array of objects
like the following.

        "data"    : [
            {

This is the ID of the alert object. Any references to a particular alert in
another API URL will require a reference to this ID property, which can be
an integer or string representation of an integer.

                "id"                       : "1",
                "name"                     : "Alert Name",

The `pivotTypeId` will be either 1 or 2. The Pivot API can describe what these
mean.

                "pivotTypeId"              : "1",

The `accountId` can be null if the alert doesn't filter by account.

                "accountId"                : "72",

The `serviceProviderId` and `serviceProviderProductId` will be null if
`pivotTypeId` is 2.

                "serviceProviderId"        : "1",
                "serviceProviderProductId" : "1",

The `serviceTypeId` and `serviceTypeCategoryId` will be null if `pivotTypeId`
is 1.

                "serviceTypeId"            : "1",
                "serviceTypeCategoryId"    : "1",

The `/alert/info` API will describe what each of these values mean.

                "comparisonTypeId"         : "1",
                "calculationTypeId"        : "1",
                "timeFrameId"              : "1",
                "valueTypeId"              : "1",
                "threshold"                : 100
            },
            {
                //etc.
            }
        ],
        "errors" : {
            "<Error ID>" : "<Error Message>"
        },
        "warnings" : {
            "<Warning ID>" : "<Warning Message>"
        }
    }


/alerts/description
-------------------
