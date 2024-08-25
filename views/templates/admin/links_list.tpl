<div class="panel">

    <h3>{$translations.created_links}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{$translations.id}</th>
                <th>{$translations.target}</th>
                <th>{$translations.campaign_id}</th>
                <th>{$translations.campaign_source}</th>
                <th>{$translations.campaign_medium}</th>
                <th>{$translations.campaign_name}</th>
                <th>{$translations.randomId}</th>
                <th>{$translations.views}</th>
                <th>{$translations.finalLink}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$links item=link}
                <tr>
                    <td>{$link.id}</td>
                    <td>{$link.target}</td>
                    <td>{$link.campaignId}</td>
                    <td>{$link.campaignSource}</td>
                    <td>{$link.campaignMedium}</td>
                    <td>{$link.campaignName}</td>
                    <td>{$link.randomId}</td>
                    <td>{$link.views}</td>
                    <td>
                        <a href="{$link.finalLink}" target="_blank">
                            {$link.finalLink}
                        </a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>