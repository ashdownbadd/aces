        <div id="real_property_panel" style="display: none; background: #fff; border: 1px solid #337ab7; border-radius: 4px; padding: 15px; margin-bottom: 15px;">
            <h4 style="margin-top:0; color:#337ab7;">Real Property Registration Assets</h4>
            <div style="display:flex; gap:10px; margin-bottom:10px;">
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">TCT No:</label>
                    <input type="text" name="tct_no" style="width:100%; padding:6px; box-sizing:border-box;">
                </div>
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">Tax Declaration No:</label>
                    <input type="text" name="tax_declaration_no" style="width:100%; padding:6px; box-sizing:border-box;">
                </div>
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">Tax Payments Status:</label>
                    <select name="real_property_status" style="width:100%; padding:6px;">
                        <option value="Updated">Updated</option>
                        <option value="Pending">Pending</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="font-size:0.9em; font-weight:bold; display:block;">Undertaking Document (PDF Only):</label>
                <input type="file" name="undertaking_doc" accept="application/pdf" style="margin-bottom:10px;">
                <label style="font-size:0.9em; font-weight:bold; display:block;">Assignment of Deed of Rights (PDF Only):</label>
                <input type="file" name="deed_of_rights_doc" accept="application/pdf">
            </div>
        </div>