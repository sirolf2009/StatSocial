<?php defined("BASEPATH") OR exit("No direct script access allowed."); ?>
<div class="container">
    <?php echo alerts(); ?>
        
    <table class="table">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts AS $post): ?>
            <tr>
                <td><?php echo $post->post_id; ?></td>
                <td><?php echo $post->type; ?></td>
                <td><?php echo $post->message; ?></td>
                <td><?php echo date("d-m-Y", $post->date); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>