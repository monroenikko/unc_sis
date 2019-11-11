                        <div class="pull-right">
                            
                        </div>
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Grade Lvl</th>
                                    <th>Tuition Fee</th>
                                    <th>Misc Fee</th>
                                    <th>Monthly Fee</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @if ($MonthlyFee)
                                    @foreach ($MonthlyFee as $data)
                                        <tr>
                                            <td>{{ $data->grade_level_id }}</td>
                                            <td>{{ $data->tuition_fee_id }}</td>
                                            <td>{{ $data->misc_fee_id }}</td>
                                            <td>{{ $data->monthly_fee }}</td>
                                            <td>{{ $data->status == 1 ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <div class="input-group-btn pull-left text-left">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action
                                                        <span class="fa fa-caret-down"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#" class="js-btn_update_sy" data-id="{{ $data->id }}">Edit</a></li>
                                                        <li><a href="#" class="js-btn_deactivate" data-id="{{ $data->id }}">Deactivate</a></li>
                                                        <li><a href="#" class="js-btn_toggle_current" data-id="{{ $data->id }}" data-toggle_title="{{ ( $data->current ? 'Remove from current active' : 'Add to current active' ) }}">{{ ( $data->current ? 'Remove from current Active' : 'Add to current Active' ) }}</a></li>
                                                    </ul>>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>